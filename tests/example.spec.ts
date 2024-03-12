import { test, expect } from '@playwright/test';

test('has title', async ({ page, baseURL } ) => {
  if (!baseURL) { throw new Error('baseURL is not defined') }
  await page.goto('./');
  // verify url
  await expect(page).toHaveURL(baseURL);
});

