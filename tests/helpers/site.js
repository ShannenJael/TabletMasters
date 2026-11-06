const publicPages = [
  '/index.php',
  '/shop.php',
  '/accessories.php',
  '/insurance.php',
  '/plans.php',
  '/register.php',
  '/support.php',
  '/tablet-games.php',
  '/reviews.php',
  '/forum.php',
  '/schools.php',
  '/healthcare-hospitals.php',
  '/business-conferences.php',
  '/about.php',
  '/privacy.php',
  '/terms.php',
  '/sms-terms.php'
];

const corePages = ['/', ...publicPages, '/shop.php?brand=Samsung'];
const internalPagesForLinkScan = publicPages;

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
  publicPages,
  internalPagesForLinkScan,
  isSameOriginUrl,
  normalizePath
};
