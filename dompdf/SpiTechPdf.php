<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class SpiTechPdf
{

	private $options;
	private $basePath, $pdfDir;
	private $generatedFilePath;
	private $dompdf;

	/*
	$config = [
    'base_path' => 'D:\wamp64\www\spsoni\billing.spitech.in\\',    
    'pdf_dir' => '../runtime/app_generated_pdf/'
	];
	*/
	function __construct($config)
	{
		$this->basePath = $config['base_path'];
		$this->pdfDir = ($config['pdf_dir'])?$config['pdf_dir']:'../runtime/app_generated_pdf/';
		
		// setting options for dompdf
		$this->options = new Options();
		$this->options->setChroot([$this->basePath]);

		// created dompdf instance
		$this->dompdf = new Dompdf($this->options);
		$this->dompdf->allowedLocalFileExtensions = ['html', 'php', 'htm'];
		$this->dompdf->setPaper('A4', 'landscape');
	}

	public function generatePDF($sourceFile, $targetFile, $forceDownload = false)
	{
		$this->dompdf->setBasePath($this->basePath);
		$this->dompdf->loadHtmlFile($sourceFile);		
		$this->dompdf->render();		
		
		// saving file to $filePath
		$filePath = $this->pdfDir . $targetFile;
		$res = file_put_contents($filePath, $this->dompdf->output());
		if ($res) {
			$this->generatedFilePath = $filePath;
		}

		// force download
		if ($forceDownload) {
			$this->dompdf->stream($targetFile, array("Attachment" => true));
			exit(0);
		}
	}

	public function getPdfPath()
	{
		return $this->generatedFilePath;
	}
}
