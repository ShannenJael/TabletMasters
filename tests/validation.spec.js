const { test, expect } = require('@playwright/test');

for (const [endpoint, target] of [
  ['send-repair.php', 'insurance.php'],
  ['send-school-quote.php', 'schools.php'],
  ['send-healthcare-quote.php', 'healthcare-hospitals.php'],
  ['send-conference-quote.php', 'business-conferences.php'],
  ['save-device.php', 'register.php']
]) {
  test(`${endpoint} rejects malformed fields without application errors`, async ({ request }) => {
    const response = await request.post('/' + endpoint, {
      form: { 'name[]': 'invalid', 'contact_name[]': 'invalid', 'email[]': 'invalid', 'phone[]': 'invalid' },
      maxRedirects: 0
    });
    expect(response.status()).toBe(302);
    expect(response.headers().location).toContain(target);
    expect(await response.text()).not.toMatch(/Warning|Fatal error|TypeError/);
  });
}

for (const path of [
  '/register.php?email[]=invalid', '/schools.php?email[]=invalid',
  '/healthcare-hospitals.php?email[]=invalid', '/business-conferences.php?email[]=invalid',
  '/accessories.php?brand[]=invalid', '/reviews.php?brand[]=invalid', '/forum.php?category[]=invalid'
]) {
  test(`${path} handles malformed query parameters`, async ({ request }) => {
    const response = await request.get(path);
    expect(response.status()).toBe(200);
    expect(await response.text()).not.toMatch(/Fatal error:|Warning:|TypeError/);
  });
}
