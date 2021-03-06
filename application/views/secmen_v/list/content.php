<?php $where = $this->session->userdata("where"); ?>
<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <b>Tuzla (Genel)</b> Seçmen Listesi
            <a class="btn btn-outline btn-primary btn-sm pull-right"
               href="<?php echo base_url("secmen/new_form"); ?>">
                <i class="fa fa-plus"></i> Seçmen Ekle
            </a>
        </h4>
    </div>
    <div class="col-md-12">
        <div class="widget p-lg">
            <h4>Arama Kriterleri</h4>
            <hr>
            <form action="<?php echo base_url("secmen/index"); ?>" method="post">
                <input type="hidden" value=1 name="mahalle">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Mahalle</label><br>
                        <select id="select2-demo-1" name="mahalle" class="form-control" data-plugin="select2">
                            <option value=""></option>
                            <?php foreach ($mahalle as $mvalue) { ?>
                                <option <?php echo ($mvalue->id === $set_mahalle || $mvalue->id == $where['mahalle']) ? "selected" : ""; ?>
                                        value="<?php echo $mvalue->id; ?>"><?php echo $mvalue->tanim; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sokak</label><br>
                        <select id="select2-demo-1" name="sokak" class="form-control" data-plugin="select2">
                            <option value=""></option>
                            <?php foreach ($sokak as $svalue) { ?>
                                <option <?php echo ($svalue->id === $set_sokak || $svalue->id == $where['sokak']) ? "selected" : ""; ?>
                                        value="<?php echo $svalue->id; ?>"><?php echo $svalue->tanim; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sandık No.</label><br>
                        <select id="select2-demo-1" name="sandik_no" class="form-control" data-plugin="select2">
                            <option value=""></option>
                            <?php foreach ($boxes as $box) { ?>
                                <option <?php echo ($box->sandiklar === $set_sandik || $box->sandiklar == $where['sandik_no']) ? "selected" : ""; ?>
                                        value="<?php echo $box->sandiklar; ?>"><?php echo $box->sandiklar; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sandık Alanları</label><br>
                        <select id="select2-demo-1" name="sandik_alani" class="form-control" data-plugin="select2">
                            <option value=""></option>
                            <?php foreach ($schools as $school) { ?>
                                <option <?php echo ($school->okullar === $set_sandik_alani || $school->okullar == $where['sandik_alani']) ? "selected" : ""; ?>
                                        value="<?php echo $school->okullar; ?>"><?php echo $school->okullar; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Vatandaşlık No.</label>
                        <input name="tckimlikno" type="text" class="form-control" value="<?php echo (isset($set_tckimlikno)) ? $set_tckimlikno : ""; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Adı</label>
                        <input name="adi" type="text" class="form-control" value="<?php echo (isset($set_adi)) ? $set_adi : ""; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Soyadı</label>
                        <input name="soyadi" type="text" class="form-control" value="<?php echo (isset($set_soyadi)) ? $set_soyadi : ""; ?>">
                    </div>
                </div>
                <div class="row">
                    <a href="<?php echo base_url("secmen/clear_session"); ?>">
                        <button type="button" class="btn btn-inverse btn-md btn-outline pull-right" style="margin-right: 12px">
                            <i class="fa fa-trash-o"></i>
                            Temizle
                        </button>
                    </a>
                    <button type="submit" class="btn btn-info btn-md btn-outline pull-right" style="margin-right: 12px">
                        <i class="fa fa-search"></i>
                        Ara
                    </button>
                </div>
            </form>

        </div>
    </div>
    <div class="col-md-12">
        <div class="widget p-lg">
            <?php if (empty($items)) { ?>
                <div class="alert alert-warning text-center" style="padding: 8px; margin-bottom: 0px; s">
                    <p style="font-size: larger">Arama sonucunda herhangi bir veri bulunamadı.</p>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <p class="pagination"><?php echo $links; ?></p>
                    </div>
                </div>
                <table id="datatable-responsive" class="table table-striped table-hover table-bordered content-container">
                    <thead>
                    <th class="w150">Adı</th>
                    <th class="w150">Soyadı</th>
                    <th class="w150">Vatandaşlık No</th>
                    <th class="w200">Sandık Alanı</th>
                    <th class="w75">Sandık No</th>
                    <th class="w75">Sandık Sıra No</th>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td class="text-center"><?php echo $item->adi; ?></td>
                            <td class="text-center"><?php echo $item->soyadi; ?></td>
                            <td class="text-center"><?php echo $item->tckimlikno; ?></td>
                            <td class="text-center"><?php echo $item->sandik_alani; ?></td>
                            <td class="text-center"><?php echo $item->sandik_no; ?></td>
                            <td class="text-center"><?php echo $item->sandik_sira; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <p>Toplam <b><?php echo number_format($count, 0, ',', '.'); ?></b> kayıt</p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="pagination"><?php echo $links; ?></p>
                </div>
            </div>
        </div><!-- .widget -->
    </div><!-- END column -->
</div>