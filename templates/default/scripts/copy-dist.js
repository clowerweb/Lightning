import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * Script to copy Nuxt.js build output to the public/dist directory
 */

// Source directory (Nuxt output)
const sourceDir = path.resolve(__dirname, '../.output/public');

// Destination directory (where PHP will serve from)
const destDir = path.resolve(__dirname, '../../../public/dist');

/**
 * Copy a directory recursively
 */
function copyDirectory(source, destination) {
  // Create the destination directory if it doesn't exist
  if (!fs.existsSync(destination)) {
    fs.mkdirSync(destination, { recursive: true });
  }

  // Get all files in the source directory
  const files = fs.readdirSync(source);

  // Copy each file or directory
  for (const file of files) {
    const sourceFilePath = path.join(source, file);
    const destFilePath = path.join(destination, file);

    // Check if it's a directory or file
    const stats = fs.statSync(sourceFilePath);
    if (stats.isDirectory()) {
      // Recursively copy subdirectories
      copyDirectory(sourceFilePath, destFilePath);
    } else {
      // Copy file
      fs.copyFileSync(sourceFilePath, destFilePath);
    }
  }
}

// Clear the destination directory if it exists
if (fs.existsSync(destDir)) {
  fs.rmSync(destDir, { recursive: true, force: true });
}

// Copy the Nuxt build to the public/dist directory
try {
  copyDirectory(sourceDir, destDir);
  console.log(`Successfully copied build files to ${destDir}`);
} catch (error) {
  console.error('Error copying build files:', error);
  process.exit(1);
}
