package im.delight.android.examples.webview;

import im.delight.android.webview.AdvancedWebView;

import android.view.Window;
import android.view.WindowManager;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.view.View;
import android.graphics.Bitmap;
import android.content.Intent;
import android.annotation.SuppressLint;
import android.os.Bundle;
import android.app.Activity;

public class MainActivity extends Activity implements AdvancedWebView.Listener {

	private static final String TEST_PAGE_URL = "file:///android_asset/index.html";
	private AdvancedWebView mWebView;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		//Remove title bar
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);

		//Remove notification bar
		this.getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
		setContentView(R.layout.activity_main);

		mWebView = (AdvancedWebView) findViewById(R.id.webview);
		mWebView.setListener(this, this);

		mWebView.setWebChromeClient(new WebChromeClient() {

			@Override
			public void onReceivedTitle(WebView view, String title) {
				super.onReceivedTitle(view, title);
				//Toast.makeText(MainActivity.this, title, Toast.LENGTH_SHORT).show();
			}

		});
		mWebView.addHttpHeader("X-Requested-With", "");
		mWebView.loadUrl(TEST_PAGE_URL);
	}

	@SuppressLint("NewApi")
	@Override
	protected void onResume() {
		super.onResume();
		mWebView.onResume();
	}

	@SuppressLint("NewApi")
	@Override
	protected void onPause() {
		mWebView.onPause();
		super.onPause();
	}

	@Override
	protected void onDestroy() {
		mWebView.onDestroy();
		super.onDestroy();
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent intent) {
		super.onActivityResult(requestCode, resultCode, intent);
		mWebView.onActivityResult(requestCode, resultCode, intent);
	}

	@Override
	public void onBackPressed() {
		if (!mWebView.onBackPressed()) { return; }
		super.onBackPressed();
	}

	@Override
	public void onPageStarted(String url, Bitmap favicon) {
		mWebView.setVisibility(View.INVISIBLE);
	}

	@Override
	public void onPageFinished(String url) {
		mWebView.setVisibility(View.VISIBLE);
	}

	@Override
	public void onPageError(int errorCode, String description, String failingUrl) {
		//Toast.makeText(MainActivity.this, "onPageError(errorCode = "+errorCode+",  description = "+description+",  failingUrl = "+failingUrl+")", Toast.LENGTH_SHORT).show();
	}

	@Override
	public void onDownloadRequested(String url, String suggestedFilename, String mimeType, long contentLength, String contentDisposition, String userAgent) {

	}

	@Override
	public void onExternalPageRequest(String url) {
		//Toast.makeText(MainActivity.this, "onExternalPageRequest(url = "+url+")", Toast.LENGTH_SHORT).show();
	}

}
