const corePages = [
  '/',
  '/index.php',
  '/shop.php',
  '/shop.php?brand=Samsung',
  '/accessories.php',
  '/insurance.php',
  '/plans.php',
  '/about.php'
];

const internalPagesForLinkScan = [
  '/index.php',
  '/shop.php',
  '/accessories.php',
  '/insurance.php',
  '/plans.php',
  '/about.php'
];

function isSameOriginUrl(candidate, baseURL) {
  try {
    const url = new URL(candidate, baseURL);
    return url.origin === new URL(baseURL).origin;
  } catch {
    return false;
  }
}

function normalizePath(candidate, baseURL) {
  const url = new URL(candidate, baseURL);
  const pathname = url.pathname === '/' ? '/index.php' : url.pathname;
  const search = url.search || '';
  return `${pathname}${search}`;
}

module.exports = {
  corePages,
  internalPagesForLinkScan,
  isSameOriginUrl,
  normalizePath
};
