"""Record a short local design walkthrough without making any lead submissions."""
from pathlib import Path
from playwright.sync_api import sync_playwright
import os

ROOT=Path(__file__).resolve().parents[2]
OUT=ROOT/'artifacts/ntmp';OUT.mkdir(parents=True,exist_ok=True)
with sync_playwright() as p:
 cached=list((Path(os.environ['LOCALAPPDATA'])/'ms-playwright').glob('chromium-*/chrome-win*/chrome.exe'))
 browser=p.chromium.launch(executable_path=str(cached[-1]) if cached else None)
 context=browser.new_context(viewport={'width':1280,'height':900},record_video_dir=str(OUT),record_video_size={'width':1280,'height':900})
 page=context.new_page();page.route('**/api/ntmp-review.php',lambda r:r.abort())
 page.goto('http://127.0.0.1:8126/landing/features/ntmp-tourism-platform.php',wait_until='networkidle')
 page.wait_for_timeout(1400)
 page.locator('.problem').scroll_into_view_if_needed();page.wait_for_timeout(900)
 page.locator('.problem-cards article').first.hover();page.wait_for_timeout(450)
 page.locator('#workflow').scroll_into_view_if_needed();page.wait_for_timeout(1000)
 page.locator('.capabilities article').nth(1).hover();page.wait_for_timeout(450)
 page.locator('#demo').scroll_into_view_if_needed();page.wait_for_timeout(650)
 page.locator('.proof').first.click();page.wait_for_timeout(1200);page.keyboard.press('Escape')
 page.locator('#review').scroll_into_view_if_needed();page.wait_for_timeout(900)
 page.locator('.faq-toggle').first.click();page.wait_for_timeout(400);page.locator('.faq-toggle').first.click();page.wait_for_timeout(600)
 page.locator('.next').click();page.wait_for_timeout(1000)
 video=page.video;context.close()
 destination=OUT/'motion-demo.webm'
 video.save_as(str(destination));Path(video.path()).unlink()
 browser.close()
 print(f'Motion walkthrough: {destination}')
