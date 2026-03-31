/**
 * Global setup — verify LocalWP site and API are accessible before running tests.
 */
export default async function globalSetup() {
  const baseURL = process.env.BASE_URL || "http://yuncosplay.local";

  // 1. Check site is reachable
  try {
    const siteRes = await fetch(baseURL, { method: "HEAD" });
    if (!siteRes.ok) {
      throw new Error(`Site returned ${siteRes.status}`);
    }
  } catch (err) {
    throw new Error(
      `LocalWP site not reachable at ${baseURL}. ` +
        `Make sure LocalWP is running.\n${err.message}`
    );
  }

  // 2. Check REST API is accessible
  try {
    const apiRes = await fetch(`${baseURL}/wp-json/api/v1/products?limit=1`);
    if (!apiRes.ok) {
      throw new Error(`API returned ${apiRes.status}`);
    }
  } catch (err) {
    throw new Error(
      `REST API not accessible at ${baseURL}/wp-json/api/v1/products.\n${err.message}`
    );
  }

  console.log(`Global setup OK — site and API reachable at ${baseURL}`);
}
