import { mergeTests } from '@playwright/test';
import { machineInfoFixture } from './machineInfoFixture';

// Merge the test objects using `mergeTests`
export const test = mergeTests(machineInfoFixture);

export { expect } from '@playwright/test';
