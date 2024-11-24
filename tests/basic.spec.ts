import { expect } from '@playwright/test';
import { test } from './fixtures/mergedFixtures';

test.describe('BBS Page Tests', () => {
  test('has correct URL and handles interactions', async ({ page, baseURL }) => {
    if (!baseURL) {
      throw new Error('baseURL is not defined');
    }

    // Navigate to the base URL
    await page.goto('./');
    await expect(page).toHaveURL(baseURL); // Verify correct URL

    // Interact with the page: Click "Generate UUID"
    await page.getByRole('button', { name: 'Generate UUID' }).click();

    // Fill out the form
    await page.getByLabel('Name:').fill('aa');
    await page.getByLabel('Text Editor:').fill('bb');

    // Submit the form
    await page.getByRole('button', { name: 'Submit' }).click();

    // Reload the page to verify updates
    await page.goto('/');

    // Assert that the new entry is visible under "Latest BBS Entries"
    await expect(page.getByRole('heading', { name: 'Latest BBS Entries' })).toHaveCount(1);
  });
});
