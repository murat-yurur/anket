<?php

class Hemsehri extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "hemsehri_v";

        if (!get_active_user())
            redirect(base_url("login"));

        /** Load Models */
        $this->load->model("hemsehri_model");
        $this->load->model("sokak_model");
        $this->load->model("mahalle_model");
    }

    public function index()
    {
        $viewData = new stdClass();

        if (isset($_SERVER['HTTP_REFERER'])) {
            $comefrom = strpos($_SERVER['HTTP_REFERER'], "hemsehri");
            if ($comefrom == false) {
                $this->session->unset_userdata("where");
            }
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $where = array();

        if ($this->input->post('adi')) {
            $where['adi'] = $this->input->post("adi");
            $viewData->set_adi = $this->input->post("adi");
            $this->session->set_userdata("where", $where);
        }

        if ($this->input->post('soyadi')) {
            $where['soyadi'] = $this->input->post("soyadi");
            $viewData->set_soyadi = $this->input->post("soyadi");
            $this->session->set_userdata("where", $where);
        }

        if ($this->input->post('tckimlikno')) {
            $where['tckimlikno'] = $this->input->post("tckimlikno");
            $viewData->set_tckimlikno = $this->input->post("tckimlikno");
            $this->session->set_userdata("where", $where);
        }

        if ($this->input->post('sokak')) {
            $where['sokak'] = $this->input->post("sokak");
            $viewData->set_sokak = $this->input->post("sokak");
            $this->session->set_userdata("where", $where);
        }

        if ($this->input->post('mahalle')) {
            $where['mahalle'] = $this->input->post("mahalle");
            $viewData->set_mahalle = $this->input->post("mahalle");
            $this->session->set_userdata("where", $where);
        }

        $condition = $this->session->userdata("where");


        $this->load->library("pagination");

        $config["base_url"] = base_url("hemsehri/index");
        $config["total_rows"] = $this->hemsehri_model->get_count($condition ? $condition : "1=1");
        $config["uri_segment)"] = 3;
        $config["per_page"] = 50;
        $config["num_links"] = 3;
        $config["last_link"] = "Son Sayfa";
        $config["first_link"] = "İlk Sayfa";

        $viewData->bina = $this->hemsehri_model->get_bina($condition ? $condition : "1=1");
        $viewData->hane = $this->hemsehri_model->get_hane($condition ? $condition : "1=1");



        $this->pagination->initialize($config);


        /** Taking all data from the table */
        $items = $this->hemsehri_model->get_records(
            $condition ? $condition : "1=1",
            $config["per_page"],
            $page,
            "mahalle, sokak, ABS(kapi), daire, soyadi, adi"
        );

        $viewData->count = $config["total_rows"];

        if ($this->input->post("mahalle"))
        {
            /** Taking all streets in town */
            $viewData->sokak = $this->sokak_model->get_all(
                array(
                    "mahalle_id" => $this->input->post("mahalle")
                ), "tanim ASC");
        } else {
            /** Taking all streets in town */
            $viewData->sokak = $this->sokak_model->get_all(array(), "tanim ASC");
        }

        /** Taking all towns in the place */
        $viewData->mahalle = $this->mahalle_model->get_all(array(), "tanim ASC");

        $this->load->model("mahalle_model");
        $this->load->model("sokak_model");

        /** Taking all town */
        $viewData->mahalle = $this->mahalle_model->get_all();

        /** Taking all streets in town */
        if ($this->input->post("mahalle"))
        {
            /** Taking all streets in town */
            $viewData->sokak = $this->sokak_model->get_all(
                array(
                    "mahalle_id" => $this->input->post("mahalle")
                ), "tanim ASC");
        } else {
            /** Taking all streets in town */
            $viewData->sokak = $this->sokak_model->get_all(array(), "tanim ASC");
        }

        $viewData->percount = $config["per_page"];

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->items = $items;
        $viewData->links = $this->pagination->create_links();


        /** Load View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function clear_session()
    {
        $this->session->unset_userdata("where");
        redirect(base_url("hemsehri"));
    }

    public function new_form()
    {
        $viewData = new stdClass();

        $this->load->model("user_role_model");
        $this->load->model("mahalle_model");
        $this->load->model("sokak_model");

        $viewData->roles = $this->user_role_model->get_all(
            array(
                "isActive" => 1
            )
        );

        /** Taking all towns */
        $viewData->mahalle = $this->mahalle_model->get_all();

        /** Taking all streets in town */
        $viewData->sokak = $this->sokak_model->get_all(array(), "tanim ASC");

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "add";

        /** Load View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function save()
    {
        $user = $this->session->userdata("user");

        /** Load Form Validation Library */
        $this->load->library("form_validation");

        /** Validation Rules */
        $this->form_validation->set_rules("adi", "Adı", "trim|required");
        $this->form_validation->set_rules("soyadi", "Soyadı", "trim|required");
        $this->form_validation->set_rules("dogumtarihi", "Doğum Tarihi", "trim|required");
        $this->form_validation->set_rules("anaadi", "Anne Adı", "trim|required");
        $this->form_validation->set_rules("babaadi", "Baba Adı", "trim|required");
        $this->form_validation->set_rules("cinsiyeti", "Cinsiyeti", "trim|required");
        $this->form_validation->set_rules("dogumyeri", "Doğum Yeri", "trim|required");
        $this->form_validation->set_rules("engellimi", "Engelli mi?", "trim|required");
        $this->form_validation->set_rules("mahalle", "Mahalle", "trim|required");
        $this->form_validation->set_rules("sokak", "Sokak", "trim|required");
        $this->form_validation->set_rules("daire", "Daire No.", "trim|required");
        $this->form_validation->set_rules("kapi", "Kapı No.", "trim|required");
        $this->form_validation->set_rules("gsm1", "Cep Telefonu (1)", "trim");
        $this->form_validation->set_rules("gsm2", "Cep Telefonu (2)", "trim");
        $this->form_validation->set_rules("eposta", "ePosta", "trim|valid_email");
        $this->form_validation->set_rules("tckimlikno", "Vatandaşlık No.", "trim|required|is_unique[secmen.tckimlikno]");

        /** Translate Validation Messages */
        $this->form_validation->set_message(
            array(
                "required" => "<b>{field}</b> alanı boş bırakılamaz...",
                "valid_email" => "Lütfen geçerli bir ePosta adresi giriniz...",
                "is_unique" => "Bu T.C. Kimlik No. sistemde kayıtlıdır."
            )
        );

        /** Run Form Validation */
        $validate = $this->form_validation->run();

        /** If Validation Successful */
        if ($validate) {
            /** Start Insert Statement */

            $var = $this->input->post("dogumtarihi");

            $dogtar = str_replace('/', '-', $var);

            $dogumtarihi = date('Y-m-d', strtotime($dogtar));

            $insert = $this->hemsehri_model->add(
                array(
                    "adi" => $this->input->post("adi"),
                    "soyadi" => $this->input->post("soyadi"),
                    "tckimlikno" => $this->input->post("tckimlikno"),
                    "gsm1" => $this->input->post("gsm1"),
                    "gsm2" => $this->input->post("gsm2"),
                    "eposta" => $this->input->post("eposta"),
                    "dogumtarihi" => $dogumtarihi,
                    "mahalle" => $this->input->post("mahalle"),
                    "sokak" => $this->input->post("sokak"),
                    "kapi" => $this->input->post("kapi"),
                    "daire" => $this->input->post("daire"),
                    "anaadi" => $this->input->post("anaadi"),
                    "babaadi" => $this->input->post("babaadi"),
                    "cinsiyeti" => $this->input->post("cinsiyeti"),
                    "dogumyeri" => $this->input->post("dogumyeri"),
                    "engellimi" => $this->input->post("engellimi"),
                    "tuzlakart" => $this->input->post("hemsehrioptions"),
                    "gorus" => $this->input->post("gorus"),
                    "createdAt" => date("Y-m-d H:i:s"),
                    "createdBy" => $user->id
                )
            );

            /** If Insert Statement Succesful */
            if ($insert) {

                /** Set the notification is Success */
                $alert = array(
                    "type" => "success",
                    "title" => "İşlem Başarılı",
                    "text" => "Seçmen kaydı başarılı bir şekilde eklendi.."
                );

                /** If Insert Statement Unsuccessful */
            } else {

                /** Set the notification is Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Seçmen kayıt işlemi esnasında bir sorun oluştu.."
                );

                $this->session->set_flashdata("alert", $alert);

                /** Redirect to Module's Add New Page */
                redirect(base_url("hemsehri/new_form"));

                die();

            }

            $this->session->set_flashdata("alert", $alert);

            /** Redirect to Module's List Page */
            redirect(base_url("hemsehri"));

            die();

            /** If Validation Unsuccessful */
        } else {
            /** Reload View and Show Error Messages Below the Inputs */
            $viewData = new stdClass();

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "add";
            $viewData->form_error = true;

            /** Reload View */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }

    }

    public function update_form($id)
    {
        $viewData = new stdClass();

        $this->load->model("user_role_model");
        $this->load->model("mahalle_model");
        $this->load->model("sokak_model");

        $viewData->roles = $this->user_role_model->get_all(
            array(
                "isActive" => 1
            )
        );

        /** Taking all towns */
        $viewData->mahalle = $this->mahalle_model->get_all();

        /** Taking all streets in town */
        $viewData->sokak = $this->sokak_model->get_all(array(), "tanim ASC");


        /** Taking the specific row's data from the table */
        $item = $this->hemsehri_model->get(
            array(
                "id" => $id
            )
        );

        $viewData->evhalki = $this->hemsehri_model->get_all(
            array(
                "sokak" => $item->sokak,
                "kapi" => $item->kapi,
                "daire" => $item->daire,
                "id!=" => $item->id
            )
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->item = $item;

        /** Load View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function update($id)
    {
        $user = $this->session->userdata("user");

        /** Load Form Validation Library */
        $this->load->library("form_validation");

        /** Validation Rules */
        $this->form_validation->set_rules("adi", "Adı", "trim|required");
        $this->form_validation->set_rules("soyadi", "Soyadı", "trim|required");
        $this->form_validation->set_rules("dogumtarihi", "Doğum Tarihi", "trim|required");
        $this->form_validation->set_rules("anaadi", "Anne Adı", "trim|required");
        $this->form_validation->set_rules("babaadi", "Baba Adı", "trim|required");
        $this->form_validation->set_rules("cinsiyeti", "Cinsiyeti", "trim|required");
        $this->form_validation->set_rules("dogumyeri", "Doğum Yeri", "trim|required");
        $this->form_validation->set_rules("engellimi", "Engelli mi?", "trim|required");
        $this->form_validation->set_rules("mahalle", "Mahalle", "trim|required");
        $this->form_validation->set_rules("sokak", "Sokak", "trim|required");
        $this->form_validation->set_rules("daire", "Daire No.", "trim|required");
        $this->form_validation->set_rules("kapi", "Kapı No.", "trim|required");
        $this->form_validation->set_rules("gsm1", "Cep Telefonu (1)", "trim");
        $this->form_validation->set_rules("gsm2", "Cep Telefonu (2)", "trim");
        $this->form_validation->set_rules("eposta", "ePosta", "trim|valid_email");
        $this->form_validation->set_rules("tckimlikno", "Vatandaşlık No.", "trim|required");

        /** Translate Validation Messages */
        $this->form_validation->set_message(
            array(
                "required" => "<b>{field}</b> alanı boş bırakılamaz...",
                "valid_email" => "Lütfen geçerli bir ePosta adresi giriniz...",
                "is_unique" => "Bu T.C. Kimlik No. sistemde kayıtlıdır."
            )
        );

        /** Run Form Validation */
        $validate = $this->form_validation->run();

        /** If Validation Successful */
        if ($validate) {

            $viewData = new stdClass();

            /** Start Update Statement */

            $this->load->model("user_role_model");
            $this->load->model("mahalle_model");
            $this->load->model("sokak_model");

            $viewData->roles = $this->user_role_model->get_all(
                array(
                    "isActive" => 1
                )
            );

            /** Taking all towns */
            $viewData->mahalle = $this->mahalle_model->get_all();

            /** Taking all streets in town */
            $viewData->sokak = $this->sokak_model->get_all(array(), "tanim ASC");

            /** Taking the specific row's data from the table */
            $item = $this->hemsehri_model->get(
                array(
                    "id" => $id
                )
            );

            $viewData->evhalki = $this->hemsehri_model->get_all(
                array(
                    "sokak" => $item->sokak,
                    "kapi" => $item->kapi,
                    "daire" => $item->daire,
                    "id!=" => $item->id
                )
            );

            $var = $this->input->post("dogumtarihi");

            $dogtar = str_replace('/', '-', $var);

            $dogumtarihi = date('Y-m-d', strtotime($dogtar));

            $data = array(
                "adi" => $this->input->post("adi"),
                "soyadi" => $this->input->post("soyadi"),
                "tckimlikno" => $this->input->post("tckimlikno"),
                "gsm1" => $this->input->post("gsm1"),
                "gsm2" => $this->input->post("gsm2"),
                "eposta" => $this->input->post("eposta"),
                "dogumtarihi" => $dogumtarihi,
                "mahalle" => $this->input->post("mahalle"),
                "sokak" => $this->input->post("sokak"),
                "kapi" => $this->input->post("kapi"),
                "daire" => $this->input->post("daire"),
                "anaadi" => $this->input->post("anaadi"),
                "babaadi" => $this->input->post("babaadi"),
                "cinsiyeti" => $this->input->post("cinsiyeti"),
                "dogumyeri" => $this->input->post("dogumyeri"),
                "engellimi" => $this->input->post("engellimi"),
                "tuzlakart" => $this->input->post("hemsehrioptions"),
                "gorus" => $this->input->post("gorus"),
                "updatedAt" => date("Y-m-d H:i:s"),
                "updatedBy" => $user->id
            );

            $update = $this->hemsehri_model->update(array("id" => $id), $data);

            /** Taking the specific row's data from the table */
            $item = $this->hemsehri_model->get(
                array(
                    "id" => $id
                )
            );

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "update";
            $viewData->form_error = true;
            $viewData->item = $item;

            /** If Update Statement Succesful */
            if ($update) {

                if ($this->input->post("gorus")) {

                    $this->load->model("talep_model");

                    $old_request = $this->talep_model->get(
                        array(
                            "istek" => $this->input->post("gorus")
                        )
                    );

                    if (!$old_request) {
                        $this->talep_model->add(
                            array(
                                "talepTarihi" => date("Y-m-d H:i:s"),
                                "istek" => $item->gorus,
                                "secmen" => $item->id,
                                "talepeden" => $item->adi . " " . $item->soyadi,
                                "kaynak" => 3,
                                "sonucDurumu" => 1
                            )
                        );
                    }
                }

                /** Set the notification is Success */
                $alert = array(
                    "type" => "success",
                    "title" => "İşlem Başarılı",
                    "text" => "Kayıt başarılı bir şekilde güncellendi.."
                );

                /** If Update Statement Unsuccessful */
            } else {

                /** Set the notification is Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Kayıt güncelleme işlemi esnasında bir sorun oluştu.."
                );

            }

            $this->session->set_flashdata("alert", $alert);

            /** Reload View */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);

            /** If Validation Unsuccessful */

        } else {
            /** Reload View and Show Error Messages Below the Inputs */
            $viewData = new stdClass();

            $this->load->model("user_role_model");
            $this->load->model("mahalle_model");
            $this->load->model("sokak_model");

            $viewData->roles = $this->user_role_model->get_all(
                array(
                    "isActive" => 1
                )
            );

            /** Taking all towns */
            $viewData->mahalle = $this->mahalle_model->get_all();

            /** Taking all streets in town */
            $viewData->sokak = $this->sokak_model->get_all(array(), "tanim ASC");


            /** Taking the specific row's data from the table */
            $item = $this->hemsehri_model->get(
                array(
                    "id" => $id
                )
            );

            $viewData->evhalki = $this->hemsehri_model->get_all(
                array(
                    "sokak" => $item->sokak,
                    "kapi" => $item->kapi,
                    "daire" => $item->daire,
                    "id!=" => $item->id
                )
            );

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "update";
            $viewData->form_error = true;
            $viewData->item = $item;

            /** Reload View */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function copy($id)
    {
        /** Taking the specific row's data from the table */
        $item = $this->hemsehri_model->get(
            array(
                "id" => $id
            )
        );

        $hane = $this->hemsehri_model->get_all(
            array(
                "sokak" => $item->sokak,
                "kapi" => $item->kapi,
                "daire" => $item->daire,
                "id!=" => $item->id
            )
        );

        $evhalki_id = array();

        for ($i = 0; $i < (count($hane)); $i++) {
            $evhalki_id[$i] = $hane[$i]->id;
        }

        foreach ($evhalki_id as $value)

            $this->hemsehri_model->update(
                array(
                    "id" => $value
                ),
                array(
                    "kanaat" => $item->kanaat,
                    "gorus" => $item->gorus,
                    "updatedAt" => $item->updatedAt,
                    "updatedBy" => $item->updatedBy
                )
            );

        $item = $this->hemsehri_model->get(
            array(
                "id" => $id
            )
        );

        $viewData = new stdClass();

        $viewData->evhalki = $this->hemsehri_model->get_all(
            array(
                "sokak" => $item->sokak,
                "kapi" => $item->kapi,
                "daire" => $item->daire,
                "id!=" => $item->id
            )
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->item = $item;

        /** Load View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
}