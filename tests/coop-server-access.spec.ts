import { test, expect } from '@playwright/test';

test('basic homepage access', async ({ page, baseURL } ) => {
  await page.goto('https://tng.coop');
  await expect(page.getByRole('heading', { name: 'Welcome to TNG Worker' })).toHaveCount(1)
});

test('basic homepage access2', async ({ page, baseURL } ) => {
  await page.goto('https://tng.coop/opendata/')
  await expect(page.getByRole('heading', { name: 'Latest BBS Entries' })).toHaveCount(1)   
});

test('basic homepage access3', async ({ page, baseURL } ) => {
  await page.goto('https://tng.coop/opendata/umb/')
  await expect(page.locator('#mapid')).toHaveCount(1)
});