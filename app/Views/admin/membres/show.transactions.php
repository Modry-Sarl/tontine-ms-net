<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-id-badge fa-fw"></i> Transactions éffectuées par <b><?= $user->ref ?> (<?= $user->user->tel ?> / <?= $user->user->username ?>)</b></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <ul class="nav flex-column nav-pills">
                            <li><a data-toggle="pill" class="nav-link text-left active" href="#entrees">
                                Recharges
                                <span class="float-right badge badge-light font-weight-bold"><?= count($entrees) ?></span> 
                            </a></li>
                            <li><a data-toggle="pill" class="nav-link text-left" href="#sorties">
                                Retraits 
                                <span class="float-right badge badge-light font-weight-bold"><?= count($sorties) ?></span> 
                            </a></li>
                            <li><a data-toggle="pill" class="nav-link text-left" href="#transferts">
                                Transferts 
                                <span class="float-right badge badge-light font-weight-bold"><?= count($transferts) ?></span> 
                            </a></li>
                        </ul>
                    </div>
                    <div class="col-md-9 col-sm-12">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade table-responsive show active" id="entrees">
                                <table class="table table-striped">
                                    <thead class="bg-light font-weight-bold">
                                        <tr>
                                            <th>#</th>
                                            <th>Numero</th>
                                            <th>Montant</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Operateur</th>
                                            <th>Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php $i = 0; foreach ($entrees as $item): ?>
                                        <tr>
                                            <td><?= ++$i ?></td>
                                            <td><?= scl_splitInt($item->numero, 2) ?></td>
                                            <td><?= number_format($item->montant, 2, '.', ' ') ?> $</td>
                                            <td><?= $item->created_at->format('D, d M Y - H:i') ?></td>
                                            <td><?= $item->message ?: ($item->statut == 1 ? 'Success' : 'Fail') ?></td>
                                            <td><?= $item->operateur ?></td>
                                            <td><?= $item->operator_transaction_id ?></td>
                                        </tr>
                                    <?php endforeach; ?></tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade table-responsive" id="sorties">
                                <table class="table table-striped">
                                    <thead class="bg-light font-weight-bold">
                                        <tr>
                                            <th>#</th>
                                            <th>Numero</th>
                                            <th>Montant</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Operateur</th>
                                            <th>Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php $i = 0; foreach ($sorties as $item): ?>
                                        <tr>
                                            <td><?= ++$i ?></td>
                                            <td><?= scl_splitInt($item->numero, 2) ?></td>
                                            <td><?= number_format($item->montant, 2, '.', ' ') ?> $</td>
                                            <td><?= $item->created_at->format('D, d M Y - H:i') ?></td>
                                            <td><?= $item->message ?: ($item->statut == 1 ? 'Success' : 'Fail') ?></td>
                                            <td><?= $item->operateur ?></td>
                                            <td><?= $item->operator_transaction_id ?></td>
                                        </tr>
                                    <?php endforeach; ?></tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade table-responsive" id="transferts">
                                <table class="table table-striped">
                                    <thead class="bg-light font-weight-bold">
                                        <tr>
                                            <th>#</th>
                                            <th>ID Beneficiaire</th>
                                            <th>Montant</th>
                                            <th>Date</th>
                                            <th>Solde débité</th>
                                            <th>Solde crédité</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php $i = 0; foreach ($transferts as $item): ?>
                                        <tr>
                                            <td><?= ++$i ?></td>
                                            <td><a class="text-dark" href="<?= link_to('admin.membre') . '?ref=' . $item->numero ?>"><?= $item->numero ?></a></td>
                                            <td><?= number_format($item->montant, 2, '.', ' ') ?> $</td>
                                            <td><?= $item->created_at->format('D, d M Y - H:i') ?></td>
                                            <td><?= $item->operateur ?></td>
                                            <td><?= $item->operator_transaction_id ?></td>
                                        </tr>
                                    <?php endforeach; ?></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>