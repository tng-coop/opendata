import { test as base, TestInfo } from '@playwright/test';
import os from 'os';

// Helper function to get non-loopback IP address
function getNonLoopbackIp(): string {
  const interfaces = os.networkInterfaces();
  for (const name of Object.keys(interfaces)) {
    for (const iface of interfaces[name] || []) {
      if (iface.family === 'IPv4' && !iface.internal) {
        return iface.address;
      }
    }
  }
  return 'No non-loopback IP found';
}

// Define a custom fixture that provides machine information
const machineInfoFixture = base.extend<{
  machineInfoFixture: { ip: string; hostname: string; osType: string; totalMemory: string };
}>({
  machineInfoFixture: async ({}, use, testInfo: TestInfo) => {
    console.log(`Running test: ${testInfo.title}`);

    const nonLoopbackIp = getNonLoopbackIp();
    const machineInfo = {
      ip: nonLoopbackIp,
      hostname: os.hostname(),
      osType: os.type(),
      totalMemory: `${(os.totalmem() / (1024 * 1024 * 1024)).toFixed(2)} GB`, // Convert bytes to GB
    };

    if (nonLoopbackIp === 'No non-loopback IP found') {
      console.warn('Warning: No non-loopback IP address detected. Ensure your machine has an active network connection.');
    }

    console.log(`Machine Info: ${JSON.stringify(machineInfo)}`);
    await use(machineInfo);
  },
});

export { machineInfoFixture };
