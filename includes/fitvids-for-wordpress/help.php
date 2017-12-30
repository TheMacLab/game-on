<h4>Basic Usage</h4>
<p>FitVids always works with YouTube, Vimeo, Blip.tv, Viddler, and Kickstarter. Use simple jQuery selectors in the fields below like <code>body</code>, <code>iframe[src^='http://mycoolvideosite.com']</code>, and <code>.ignore-item, .ignore-section</code>.</p>
<p>The creator of the jQuery FitVids plugin has a video with an in depth tutorial on how FitVids works. You can <a href="http://fitvidsjs.com/" target="_blank">watch the video on fitvidsjs.com</a>.</p>
<h4>About Extras</h4>
<p>In some cases your theme might not have jQuery installed and FitVids will not be working. Check the "<strong>Use Google CDN</strong>" checkbox to have jQuery added to your theme when it is not installed.</p>
<h4>Using FitVids Examples</h4>
<p>Here are some examples to help you configure FitVids:</p>
<ol>
    <li>In the "<strong>FitVids Main Selector</strong>" field enter <code>body</code> to apply FitVids to every video. This is also the default setting for the plugin.</li>
    <li>In the "<strong>FitVids Custom Selector</strong>" field enter <code>iframe[src^='http://mycoolvideosite.com']</code> to have FitVids applied to every iframe video from the url "http://mycoolvideosite.com". If you are using "https" be sure to account for your protocol as well.</li>
    <li>In the "<strong>FitVids Custom Selector</strong>" field enter <code>iframe[src^='http://mycoolvideosite.com'], iframe[src^='http://mycoolvideosite2.com']</code> to have FitVids applied to every iframe video from the urls "http://mycoolvideosite.com" and "http://mycoolvideosite2.com". If you are using "https" be sure to account for your protocol as well.</li>
    <li>In the "<strong>FitVids Ignore Selector</strong>" field enter <code>.ignore-this-video</code> to have every video with the HTML class or contained within an HTML class of ".ignore-this-video" ignored by FitVids. These videos will not have FitVids applied to them.</li>
</ol>