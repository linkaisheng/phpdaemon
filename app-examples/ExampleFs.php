<?php

/**
 * @package    Examples
 * @subpackage Base
 *
 * @author     Zorin Vasily <maintainer@daemon.io>
 */
class ExampleFs extends \PHPDaemon\AppInstance {
	/**
	 * Creates Request.
	 * @param object Request.
	 * @param object Upstream application instance.
	 * @return object Request.
	 */
	public function beginRequest($req, $upstream) {
		return new ExampleFsRequest($this, $upstream, $req);
	}
}

class ExampleFsRequest extends HTTPRequest {

	public function init() {
		$req = $this;
		$this->sleep(1, true);
		\PHPDaemon\FS::readfile('/etc/filesystems', function ($file, $data) use ($req) {
			$req->fileData = $data;
			$req->wakeup();
		});
	}

	/**
	 * Called when request iterated.
	 * @return integer Status.
	 */
	public function run() {
		$this->header('Content-Type: text/plain');
		echo "Contents of /etc/filesystems:\n" . $this->fileData;
	}

}
