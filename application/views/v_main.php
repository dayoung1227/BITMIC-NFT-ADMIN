
<!-- s: Main Contents -->
<div class="row page_title">
    <div class="col-sm-6">
        <h4><?=COINAME;?> Dashboard</h4>
    </div>
    <div class="col-sm-6">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html">Home</a>
            </li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="box bg_primary">
            <div class="box-head d-flex align-items-center justify-content-between">
                <h3>Bitcoin</h3>
                <i title="BTC" class="cc BTC-alt"></i>
            </div>
            <div class="box-body d-flex align-items-center justify-content-between">
                <span>1 BTC = 6,695.90 USD </span>
                <span>+1.35%</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="box bg_info">
            <div class="box-head d-flex align-items-center justify-content-between">
                <h3>Ethereum</h3>
                <i class="cc ETC" title="ETC"></i>
            </div>
            <div class="box-body d-flex align-items-center justify-content-between">
                <span>1 ETH = 478.24 USD </span>
                <span>-5.35%</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="box bg_secondary">
            <div class="box-head d-flex align-items-center justify-content-between">
                <h3>Litecoin</h3>
                <i class="cc LTC-alt" title="LTC"></i>
            </div>
            <div class="box-body d-flex align-items-center justify-content-between">
                <span>1 LTC - 81.6 USD </span>
                <span>+3.35%</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="box bg_success">
            <div class="box-head d-flex align-items-center justify-content-between">
                <h3>Neo</h3>
                <i class="cc NEO" title="NEO"></i>
            </div>
            <div class="box-body d-flex align-items-center justify-content-between">
                <span>1 NEO - 38.54 USD </span>
                <span>-2.35%</span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">Token Sale End In</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="tk_countdown_time text-center" data-time="2018/09/06 00:00:00"></div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Sale Raised</span>
                            <span>Soft-caps</span>
                        </div>
                        <div class="progressbar">
                            <div class="progress-bar gradient" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width:46%"> </div>
                            <span class="progress_label" style="left: 30%"> <strong> 46,000 ICC </strong></span>
                            <span class="progress_label" style="left: 75%"> <strong> 90,000 ICC </strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header text_md_center">TOKEN BALANCE</div>
            <div class="card-body">
                <div class="row align-items-center text_md_center">
                    <div class="col-xl-6 col-lg-12">
                        <h5 class="text-muted small">Total Balance</h5>
                        <h3 class="balance_text">$7,254.26</h3>
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <button class="btn btn-default">Withdraw</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header text_md_center">Token Sale Proceeds</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small">Sale Raised</span>
                    <span class="small">Soft-caps</span>
                </div>
                <div class="progressbar">
                    <div style="width:46%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="46" role="progressbar" class="progress-bar gradient"> </div>
                    <span style="left: 30%" class="progress_label"> <strong> 46,000 ICC </strong></span>
                    <span style="left: 75%" class="progress_label"> <strong> 90,000 ICC </strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Transactions List</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Type</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td><i class="cc BTC-alt" title="BTC"></i>BTC</td>
                            <td><span class="badge-success badge-pill">Completed</span></td>
                            <td>0.46872</td>
                            <td>2018-01-31 06:52:40</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><i class="cc ETH-alt" title="ETH"></i>ETH</td>
                            <td><span class="badge-warning badge-pill">Pending</span></td>
                            <td>0.31552</td>
                            <td>2018-02-05 05:45:15</td>
                            <td class="text-danger">Sell</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><i class="cc ETH-alt" title="ETH"></i>ETH</td>
                            <td><span class="badge-warning badge-pill">Pending</span></td>
                            <td>0.25421</td>
                            <td>2018-10-08 02:30:26</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td><i class="cc NEO" title="NEO"></i>NEO</td>
                            <td><span class="badge-danger badge-pill">Cancelled</span></td>
                            <td>0.87261</td>
                            <td>2018-05-15 06:12:14</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td><i class="cc LTC-alt" title="LTC"></i>LTC</td>
                            <td><span class="badge-info badge-pill">in Process</span></td>
                            <td>0.15612</td>
                            <td>2018-04-24 07:10:31</td>
                            <td class="text-danger">Sell</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td><i class="cc BTC-alt" title="BTC"></i>BTC</td>
                            <td><span class="badge-danger badge-pill">Cancelled</span></td>
                            <td>0.65842</td>
                            <td>2018-04-20 07:10:31</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td><i class="cc BTC-alt" title="BTC"></i>BTC</td>
                            <td><span class="badge-success badge-pill">Completed</span></td>
                            <td>0.46872</td>
                            <td>2018-01-31 06:52:40</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td><i class="cc ETH-alt" title="ETH"></i>ETH</td>
                            <td><span class="badge-warning badge-pill">Pending</span></td>
                            <td>0.31552</td>
                            <td>2018-02-05 05:45:15</td>
                            <td class="text-danger">Sell</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td><i class="cc ETH-alt" title="ETH"></i>ETH</td>
                            <td><span class="badge-success badge-pill">Completed</span></td>
                            <td>0.25421</td>
                            <td>2018-10-08 02:30:26</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td><i class="cc NEO" title="NEO"></i>NEO</td>
                            <td><span class="badge-danger badge-pill">Cancelled</span></td>
                            <td>0.87261</td>
                            <td>2018-05-15 06:12:14</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td><i class="cc LTC-alt" title="LTC"></i>LTC</td>
                            <td><span class="badge-info badge-pill">in Process</span></td>
                            <td>0.15612</td>
                            <td>2018-04-24 07:10:31</td>
                            <td class="text-danger">Sell</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td><i class="cc BTC-alt" title="BTC"></i>BTC</td>
                            <td><span class="badge-danger badge-pill">Cancelled</span></td>
                            <td>0.65842</td>
                            <td>2018-04-20 07:10:31</td>
                            <td class="text-success">Buy</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- e: Main Contents -->



