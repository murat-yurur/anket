<?php $where = $this->session->userdata("where"); ?>
<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <b>TuzlaKart</b>'ı var olan seçmen listesi
            <a class="btn btn-outline btn-primary btn-sm pull-right"
               href="<?php echo base_url("tuzlakart/new_form"); ?>">
                <i class="fa fa-plus"></i> Seçmen Ekle
            </a>
        </h4>
    </div>
    <div class="col-md-12">
        <div class="widget p-lg">
            <h4>Arama Kriterleri</h4>
            <hr>
            <form action="<?php echo base_url("tuzlakart/index"); ?>" method="post">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Mahalle</label><br>
                        <select id="mahalle" name="mahalle" class="form-control" data-plugin="select2">
                            <option value=""></option>
                            <?php foreach ($mahalle as $mvalue) { ?>
                                <option <?php echo ($mvalue->id === $set_mahalle || $mvalue->id == $where['mahalle']) ? "selected" : ""; ?>
                                        value="<?php echo $mvalue->id; ?>"><?php echo $mvalue->tanim; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Sokak</label><br>
                        <select id="select2-demo-1" name="sokak" class="form-control" data-plugin="select2">
                            <option value=""></option>
                            <?php foreach ($sokak as $svalue) { ?>
                                <option <?php echo ($svalue->id === $set_sokak || $svalue->id == $where['sokak']) ? "selected" : ""; ?>
                                        value="<?php echo $svalue->id; ?>"><?php echo $svalue->tanim; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Adı</label>
                        <input name="adi" type="text" class="form-control" value="<?php echo (isset($set_adi)) ? $set_adi : ""; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Soyadı</label>
                        <input name="soyadi" type="text" class="form-control" value="<?php echo (isset($set_soyadi)) ? $set_soyadi : ""; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Vatandaşlık No.</label>
                        <input name="tckimlikno" type="text" class="form-control" value="<?php echo (isset($set_tckimlikno)) ? $set_tckimlikno : ""; ?>">
                    </div>
                </div>
                <div class="row">
                        <a class="btn btn-outline btn-success btn-md pull-right"
                           href="<?php echo base_url("tuzlakart/excel"); ?>">
                            <i class="fa fa-file-excel-o"></i> Excel
                        </a>
                    <a href="<?php echo base_url("tuzlakart/clear_session"); ?>">
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
                <table id="datatable-responsive"
                       class="table table-striped table-hover table-bordered content-container">
                    <thead>
                    <th class="w150">Adı</th>
                    <th class="w150">Soyadı</th>
                    <th class="w100">Mahalle</th>
                    <th class="w100">Sokak</th>
                    <th class="w50">Kapı No</th>
                    <th class="w50">Daire No</th>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td class="text-center"><?php echo $item->adi; ?></td>
                            <td class="text-center"><?php echo $item->soyadi; ?></td>
                            <td class="text-center"><?php echo get_townname($item->mahalle); ?></td>
                            <td class="text-center"><?php echo get_streetname($item->sokak); ?></td>
                            <td class="text-center"><?php echo $item->kapi; ?></td>
                            <td class="text-center"><?php echo $item->daire; ?></td>
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