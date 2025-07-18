const { Builder, By, until } = require('selenium-webdriver');
const firefox = require('selenium-webdriver/firefox');

(async function main() {
  let options = new firefox.Options();
  options.addArguments("-headless");  // Remove this line if you want visible browser

  let driver = await new Builder()
    .forBrowser('firefox')
    .setFirefoxOptions(options)
    .build();

  try {
    // 1. Visit login page
    await driver.get('http://localhost:8000/login.php');

    // 2. Fill in username and password fields
    await driver.findElement(By.name('username')).sendKeys('admin');
    await driver.findElement(By.name('password')).sendKeys('supersecret_youwillNEVERguessthis');

    // 3. Submit the login form
    await driver.findElement(By.css('form')).submit();

    // 4. Wait until logged in (wait for URL or known element)
    await driver.wait(until.urlContains('index.php'), 5000);

    console.log('Logged in!');

    while (true) {
      // 5. Visit the main page listing shared pastes
      await driver.get('http://localhost:8000/index.php');

      // 6. Wait for the shared section
      await driver.wait(until.elementLocated(By.xpath("//h2[contains(text(),'Shared With You')]")), 5000);

      // 7. Get fresh list of shared paste links
      let sharedPasteLinks = await driver.findElements(By.xpath("//h2[contains(text(),'Shared With You')]/following-sibling::ul[1]/li/a"));

      // 8. Extract href and title into an array to avoid stale element refs
      let pastes = [];
      for (const link of sharedPasteLinks) {
        let url = await link.getAttribute('href');
        let titleText = await link.getText();
        pastes.push({ url, titleText });
      }

      console.log(`Found ${pastes.length} shared paste(s).`);

      // 9. Visit each shared paste URL from the array
      for (const paste of pastes) {
        console.log(`Visiting shared paste: ${paste.titleText} -> ${paste.url}`);

        await driver.get(paste.url);

        // Wait for paste page title to include the paste title
        await driver.wait(until.titleContains(paste.titleText), 5000).catch(() => {});

        let title = await driver.getTitle();
        console.log('Visited paste page title:', title);

        // Pause between visits to avoid overloading
        await new Promise(res => setTimeout(res, 1000));
      }

      console.log('Waiting 10 seconds before refreshing shared pastes...');
      await new Promise(res => setTimeout(res, 10000));
    }

  } finally {
    await driver.quit();
  }
})();

