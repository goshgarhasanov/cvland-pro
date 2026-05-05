import { chromium } from 'playwright'
import { mkdir } from 'node:fs/promises'
import path from 'node:path'

const OUT_DIR = path.resolve(process.cwd(), '../docs/screenshots')
const WEB = 'http://localhost:5173'
const API = 'http://127.0.0.1:8001'

await mkdir(OUT_DIR, { recursive: true })

const browser = await chromium.launch()

// Desktop captures (1440x900 — standard SaaS laptop)
const desktop = await browser.newContext({
  viewport: { width: 1440, height: 900 },
  deviceScaleFactor: 2,
  colorScheme: 'dark',
})

// Mobile captures (iPhone 14 Pro)
const mobile = await browser.newContext({
  viewport: { width: 393, height: 852 },
  deviceScaleFactor: 3,
  colorScheme: 'dark',
  isMobile: true,
  hasTouch: true,
})

async function capture(ctx, url, file, opts = {}) {
  const page = await ctx.newPage()
  await page.goto(url, { waitUntil: 'networkidle' })
  if (opts.scrollTo) {
    await page.evaluate((sel) => document.querySelector(sel)?.scrollIntoView({ block: 'start' }), opts.scrollTo)
    await page.waitForTimeout(500)
  }
  if (opts.wait) await page.waitForTimeout(opts.wait)
  await page.screenshot({
    path: path.join(OUT_DIR, file),
    fullPage: opts.fullPage ?? false,
    type: 'png',
  })
  console.log(`✓ ${file}`)
  await page.close()
}

// ─── Marketing site (desktop) ────────────────────────────────────────────────
await capture(desktop, `${WEB}/`, '01-home-hero.png', { wait: 1500 })
await capture(desktop, `${WEB}/`, '02-home-full.png', { fullPage: true, wait: 1500 })
await capture(desktop, `${WEB}/auth/login`, '03-login.png', { wait: 800 })
await capture(desktop, `${WEB}/auth/register`, '04-register.png', { wait: 800 })

// ─── Marketing site (mobile) ─────────────────────────────────────────────────
await capture(mobile, `${WEB}/`, '05-mobile-hero.png', { wait: 1500 })
await capture(mobile, `${WEB}/`, '06-mobile-full.png', { fullPage: true, wait: 1500 })
await capture(mobile, `${WEB}/auth/login`, '07-mobile-login.png', { wait: 800 })

// ─── Admin panel ─────────────────────────────────────────────────────────────
const adminPage = await desktop.newPage()
await adminPage.goto(`${API}/admin/login`, { waitUntil: 'networkidle' })
await adminPage.waitForTimeout(800)
await adminPage.screenshot({ path: path.join(OUT_DIR, '08-admin-login.png'), type: 'png' })
console.log('✓ 08-admin-login.png')

// Authenticate via the local-only dev shortcut (Filament's Livewire form is brittle to scripted submission).
await adminPage.goto(`${API}/__dev/login-admin`, { waitUntil: 'networkidle' })
await adminPage.waitForTimeout(2000)
await adminPage.waitForLoadState('networkidle').catch(() => null)
await adminPage.waitForTimeout(1500)
console.log('  current admin URL:', adminPage.url())

await adminPage.screenshot({ path: path.join(OUT_DIR, '09-admin-dashboard.png'), fullPage: true, type: 'png' })
console.log('✓ 09-admin-dashboard.png')

await adminPage.goto(`${API}/admin/users`, { waitUntil: 'networkidle' })
await adminPage.waitForTimeout(1000)
await adminPage.screenshot({ path: path.join(OUT_DIR, '10-admin-users.png'), type: 'png' })
console.log('✓ 10-admin-users.png')

await adminPage.goto(`${API}/admin/templates`, { waitUntil: 'networkidle' })
await adminPage.waitForTimeout(1000)
await adminPage.screenshot({ path: path.join(OUT_DIR, '11-admin-templates.png'), type: 'png' })
console.log('✓ 11-admin-templates.png')

await adminPage.goto(`${API}/admin/cvs`, { waitUntil: 'networkidle' })
await adminPage.waitForTimeout(1000)
await adminPage.screenshot({ path: path.join(OUT_DIR, '12-admin-cvs.png'), type: 'png' })
console.log('✓ 12-admin-cvs.png')

await adminPage.goto(`${API}/admin/orders`, { waitUntil: 'networkidle' })
await adminPage.waitForTimeout(1000)
await adminPage.screenshot({ path: path.join(OUT_DIR, '13-admin-orders.png'), type: 'png' })
console.log('✓ 13-admin-orders.png')

// ─── API docs (Scramble) ─────────────────────────────────────────────────────
await capture(desktop, `${API}/docs/api`, '14-api-docs.png', { wait: 2000 })

await browser.close()
console.log(`\nAll screenshots saved to ${OUT_DIR}`)
