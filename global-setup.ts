import { FullConfig } from '@playwright/test';
import { exec } from 'child_process';
import util from 'util';

const execPromise = util.promisify(exec);

async function globalSetup(config: FullConfig) {
  console.log('Running global setup...');

  try {
    // Run the setup script (e.g., database setup)
    await execPromise('./reset-database.sh');
    console.log('Database setup completed.');
  } catch (error) {
    console.error('Error during global setup:', error);
    throw error;
  }
}

export default globalSetup;
