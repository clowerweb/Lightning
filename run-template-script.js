const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Get the script name (e.g., 'dev', 'build') from the command-line arguments
const script = process.argv[2];
if (!script) {
    console.error('Error: No script specified. Usage: node run-template-script.js <script_name>');
    process.exit(1);
}

const runScriptForTemplate = (templateName) => {
    // Construct the path to the template directory
    const templateDir = path.resolve(__dirname, 'templates', templateName);

    if (!fs.existsSync(templateDir) || !fs.existsSync(path.join(templateDir, 'package.json'))) {
        console.error(`Error: Template directory or package.json not found for site: "${templateName}"`);
        console.error(`Looked for package.json in: ${templateDir}`);
        process.exit(1);
    }

    if (script === 'install') {
        console.log(`Running "npm ${script}" in /templates/${templateName}...`);

        try {
            execSync(`npm ${script}`, {
                cwd: templateDir,
                stdio: 'inherit'
            });
        } catch (error) {
            console.error(`Error running "npm ${script}" for site: "${templateName}"`);
            process.exit(1);
        }

        const adminDir = path.resolve(__dirname, 'templates', 'admin');
        console.log(`Running "npm ${script}" in /templates/admin...`);

        try {
            execSync(`npm ${script}`, {
                cwd: adminDir,
                stdio: 'inherit'
            });
        } catch (error) {
            console.error(`Error running "npm ${script}" for admin"`);
            process.exit(1);
        }

        process.exit(0);
    }

    // Execute the npm script in the template's directory
    console.log(`Running "npm run ${script}" in /templates/${templateName}...`);
    try {
        execSync(`npm run ${script}`, {
            cwd: templateDir,
            stdio: 'inherit'
        });
    } catch (error) {
        console.error(`Error running "npm run ${script}" for site: "${templateName}"`);
        process.exit(1);
    }
};

if (script === 'build') {
    const templatesToBuild = ['default', 'admin'];
    console.log('Building all templates...');
    for (const template of templatesToBuild) {
        runScriptForTemplate(template);
    }
    console.log('All templates built successfully.');
} else {
    // Read .env file to find the SITE_TEMPLATE for other scripts like 'dev'
    const envPath = path.resolve(__dirname, '.env');
    let siteTemplate = 'default'; // Default value

    if (fs.existsSync(envPath)) {
        const envConfig = fs.readFileSync(envPath, 'utf8');
        const match = envConfig.match(/^SITE_TEMPLATE=(.*)$/m);
        if (match && match[1]) {
            siteTemplate = match[1].trim();
        }
    }
    runScriptForTemplate(siteTemplate);
}
