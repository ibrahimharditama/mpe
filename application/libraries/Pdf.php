<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
class Pdf extends Dompdf{
    /**
     * PDF filename
     * @var String
     */
    public $filename;
    public function __construct(){
        parent::__construct();
        $this->filename = "laporan.pdf";
    }
    /**
     * Get an instance of CodeIgniter
     *
     * @access    protected
     * @return    void
     */
    protected function ci()
    {
        return get_instance();
    }
    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access    public
     * @param    string    $view The view to load
     * @param    array    $data The view data
     * @return    void
     */
    public function load_view($view, $data = array(),$ukuran,$orientasi,$filename){
        $this->set_paper($ukuran, $orientasi);
        $html = $this->ci()->load->view($view, $data, TRUE);
        $this->load_html($html);
        $this->render();
        $this->stream($filename, array("Attachment" => false));
    }

    public function load_pdf($view, $data = array(), $filename)
    {
        $html = $this->ci()->load->view($view, $data, TRUE);

		$dompdf = new Dompdf([
			"isRemoteEnabled" => true,
			]);

		$dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream($filename, array("Attachment" => false));
    }
}