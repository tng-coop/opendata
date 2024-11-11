import { test, expect } from '@playwright/test';

test('has correct URL', async ({ page, baseURL } ) => {
  if (!baseURL) { throw new Error('baseURL is not defined') }
  await page.goto('./');
  // verify url
  await expect(page).toHaveURL(baseURL);

  await page.getByRole('button', { name: 'Generate UUID' }).click();
  await page.getByLabel('Name:').fill('aa');
  await page.getByLabel('Text Editor:').fill('bb');
  await page.getByRole('button', { name: 'Submit' }).click();
  await page.goto('/');
  
  await expect(page.getByRole('heading', { name: 'Latest BBS Entries' })).toHaveCount(1);
});

