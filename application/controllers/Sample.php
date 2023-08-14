<?php

defined('BASEPATH') or exit('No direct script access allowed');

use MesseEsang\App\Fair;

class Sample extends CI_Controller
{

    public function index()
    {
        $this->load->view('sample/index');
    }

    public function json()
    {
        $this->load->view('sample/json');
    }

    public function form()
    {
        $fair = new Fair(
            '2023 코리아빌드(킨텍스)',
            new DateTime('2023-03-01'),
            new DateTime('2023-03-06'),
            ['abcd', '1234', '하하호호'],
        );

        $this->load->view('sample/form', ['fair' => $fair]);
    }

    public function php_serialize()
    {
        $fair = new Fair(
            '2023 코리아빌드(킨텍스)',
            new DateTime('2023-03-01'),
            new DateTime('2023-03-06'),
        );

        $fairText = serialize($fair);

        file_put_contents(FCPATH . 'uploads/fair.txt', $fairText);

        dump($fair, $fairText);

        echo <<< HTML
        <a href="php_unserialize">역직렬화 보기</a>
        HTML;
    }

    public function php_unserialize()
    {
        $fair = unserialize(file_get_contents(FCPATH . 'uploads/fair.txt'));

        dump($fair);
    }

    public function save()
    {
        $all = $this->input->post();

        $fair = $this->input->post('fair');

        dump($all, $fair);
    }

    public function api_fair()
    {
        $fair = new Fair(
            $this->input->get('fairName') ?? '2023 코리아빌드(킨텍스)',
            new DateTime('2023-03-01'),
            new DateTime('2023-03-06'),
            ['abcd', '1234', '하하호호'],
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($fair, JSON_UNESCAPED_UNICODE));
    }
}

/* End of file Example.php */
