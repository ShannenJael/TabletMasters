// Local test server for Playwright.
//
// PHP's built-in server (`php -S`) handles one connection at a time and, on
// Windows, can sit blocked on an idle socket that Chromium opens speculatively.
// With several Playwright workers that stalls page loads for 10s+ and causes
// random timeouts. This script starts a small pool of `php -S` processes and a
// Node reverse proxy in front of them. The proxy only opens upstream
// connections for real requests, and the pool gives genuine concurrency.
// Output buffering is disabled because buffered output from `php -S` on
// Windows intermittently arrives truncated.
//
// Env: HOST (default 127.0.0.1), PORT (default 3100),
//      PHP_BINARY (default "php"), PHP_POOL_SIZE (default 4).
//      PHP_DOC_ROOT and PHP_EXTRA_ARGS (JSON array) support isolated test apps.

const http = require('http');
const path = require('path');
const { spawn } = require('child_process');

const HOST = process.env.HOST || '127.0.0.1';
const PORT = Number(process.env.PORT || 3100);
const PHP = process.env.PHP_BINARY || 'php';
const POOL_SIZE = Math.max(1, Number(process.env.PHP_POOL_SIZE || 4));
const docRoot = process.env.PHP_DOC_ROOT || path.resolve(__dirname, '..', '..');
const extraArgs = JSON.parse(process.env.PHP_EXTRA_ARGS || '[]');

const upstreamPorts = Array.from({ length: POOL_SIZE }, (_, i) => PORT + 1 + i);
const children = upstreamPorts.map(port => {
  // output_buffering=0: with php.ini's usual 4096, `php -S` on Windows
  // intermittently truncates large pages (e.g. shop.php cut mid-script).
  const child = spawn(PHP, [...extraArgs, '-d', 'output_buffering=0', '-S', `${HOST}:${port}`, '-t', docRoot], {
    cwd: docRoot,
    stdio: ['ignore', 'ignore', 'inherit']
  });
  child.on('exit', code => {
    if (!shuttingDown) {
      console.error(`php -S on port ${port} exited with code ${code}`);
      shutdown(1);
    }
  });
  child.on('error', err => {
    console.error(`Failed to start PHP (${PHP}): ${err.message}. Set PHP_BINARY or add php to PATH.`);
    shutdown(1);
  });
  return child;
});

const agent = new http.Agent({ keepAlive: false });
let next = 0;

const server = http.createServer((req, res) => {
  const port = upstreamPorts[next++ % upstreamPorts.length];
  const upstream = http.request({
    host: HOST,
    port,
    method: req.method,
    path: req.url,
    headers: { ...req.headers, host: `${HOST}:${PORT}`, connection: 'close' },
    agent
  }, upstreamRes => {
    res.writeHead(upstreamRes.statusCode || 502, upstreamRes.headers);
    upstreamRes.pipe(res);
  });
  upstream.on('error', err => {
    if (!res.headersSent) {
      res.writeHead(502, { 'content-type': 'text/plain' });
    }
    res.end(`Upstream PHP error: ${err.message}`);
  });
  req.pipe(upstream);
});

let shuttingDown = false;
function shutdown(code = 0) {
  if (shuttingDown) return;
  shuttingDown = true;
  server.close();
  children.forEach(child => child.kill());
  process.exit(code);
}
['SIGINT', 'SIGTERM', 'SIGHUP'].forEach(sig => process.on(sig, () => shutdown(0)));

// Give the PHP processes a moment to bind before accepting traffic.
setTimeout(() => {
  server.listen(PORT, HOST, () => {
    console.log(`PHP test server pool (${POOL_SIZE}) at http://${HOST}:${PORT}`);
  });
}, 750);
