<?php

class Talep_durumu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "talep_durumu_v";

        if (!get_active_user())
            redirect(base_url("login"));

        /** Load Models */
        $this->load->model("talep_durumu_model");
    }

    public function index()
    {
        $viewData = new stdClass();

        /** Taking all data from the table */
        $items = $this->talep_durumu_model->get_all(
            array()
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->items = $items;

        /** Load View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function new_form()
    {
        $viewData = new stdClass();

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
        $this->form_validation->set_rules("title", "Tanım", "trim|required|is_unique[talep_durumu.title]");

        /** Translate Validation Messages */
        $this->form_validation->set_message(
            array(
                "required" => "<b>{field}</b> alanı boş bırakılamaz...",
            )
        );

        /** Run Form Validation */
        $validate = $this->form_validation->run();

        /** If Validation Successful */
        if ($validate) {
            /** Start Insert Statement */

            $insert = $this->talep_durumu_model->add(
                array(
                    "title" => $this->input->post("title"),
                    "isActive" => 1,
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
                    "text" => "Kayıt başarılı bir şekilde eklendi.."
                );

                $this->session->set_flashdata("alert", $alert);

                /** Redirect to Module's Add New Page */
                redirect(base_url("talep_durumu"));

                die();

                /** If Insert Statement Unsuccessful */
            } else {

                /** Set the notification is Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Kayıt işlemi esnasında bir sorun oluştu.."
                );

                $this->session->set_flashdata("alert", $alert);

                /** Redirect to Module's Add New Page */
                redirect(base_url("talep_durumu/new_form"));

                die();

            }

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

        /** Taking the specific row's data from the table */
        $item = $this->talep_durumu_model->get(
            array(
                "id" => $id
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

        $old_talep_durumu = $this->talep_durumu_model->get(
            array(
                "id" => $id
            )
        );

        /** Validation Rules */
        $this->form_validation->set_rules("title", "Rol Adı", "trim|required|is_unique[talep_durumu.title]");

        /** Translate Validation Messages */
        $this->form_validation->set_message(
            array(
                "required" => "<b>{field}</b> alanı boş bırakılamaz...",
                "is_unique" => "Bu isimde bir rol daha önceden tanımlanmış..."
            )
        );

        /** Run Form Validation */
        $validate = $this->form_validation->run();

        /** If Validation Successful */
        if ($validate) {

            /** Start Update Statement */
            $update = $this->talep_durumu_model->update(
                array(
                    "id" => $id
                ),
                array(
                    "title" => $this->input->post("title"),
                    "updatedAt" => date("Y-m-d H:i:s"),
                    "updatedBy" => $user->id
                )
            );

            /** If Update Statement Succesful */
            if ($update) {

                /** Set the notification is Success */
                $alert = array(
                    "type" => "success",
                    "title" => "İşlem Başarılı",
                    "text" => "Kayıt başarılı bir şekilde güncellendi.."
                );

                /** Redirect to Module's List Page */
                redirect(base_url("talep_durumu"));

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

            /** Redirect to Module's List Page */
            redirect(base_url("talep_durumu/update_form/$id"));

            /** If Validation Unsuccessful */
        } else {
            /** Reload View and Show Error Messages Below the Inputs */
            $viewData = new stdClass();

            /** Taking the specific row's data from the table */
            $item = $this->talep_durumu_model->get(
                array(
                    "id" => $id
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

    public function delete($id)
    {
        /** Taking the specific row's data from talep_durumus table */
        $item = $this->talep_durumu_model->get(
            array(
                "id" => $id
            )
        );
        /** Starting Delete Statement */
        $delete = $this->talep_durumu_model->delete(
            array(
                "id" => $id
            )
        );

        /** If Delete Statement is Succesful */
        if ($delete) {

            /** Set the notification is Success */
            $alert = array(
                "type" => "success",
                "title" => "İşlem Başarılı",
                "text" => "<b>$item->title</b> başarılı bir şekilde silindi.."
            );

            /** If Delete Statement is Unsuccessful */
        } else {

            /** Set the notification is Error */
            $alert = array(
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Kayıt silme işlemi esnasında bir sorun oluştu.."
            );

        }

        $this->session->set_flashdata("alert", $alert);

        /** Redirect to Module's List Page */
        redirect(base_url("talep_durumu"));
    }

    public function isActiveSetter($id)
    {
        /** If the posted data is true then set the isActive variable's value 1 else set 0 */
        $isActive = ($this->input->post("data") === "true") ? 1 : 0;

        /** Update the isActive column with isActive varible's value */
        $this->talep_durumu_model->update(
            array(
                "id" => $id
            ),
            array(
                "isActive" => $isActive
            )
        );
    }
}