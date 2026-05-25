


          content="IE=edge">

          content="width=device-width, initial-scale=1">


    <style type="text/css">

        .panel.panel-green {
            border-radius: 0;
            box-shadow: 0 0 10px #888888;
            border-color: #0C8249;
        }

        .panel.panel-green .panel-heading {
            border-radius: 0;
            color: #FFF;
            background-color: #0C8249;
        }

        .panel.panel-green .panel-body {
            background-color: #F2F2F2;
            color: #0C8249;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }

        .btn-circle.btn-lg {
            width: 50px;
            height: 50px;
            padding: 10px 16px;
            font-size: 18px;
            line-height: 1.33;
            border-radius: 25px;
        }

        .btn-circle.btn-xl {
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            font-size: 24px;
            line-height: 1.33;
            border-radius: 35px;
        }

        #loader {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 175px;
            height: 175px;
        }

        #loader .dot {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 100px;
            height: 100%;
        }

        #loader .dot::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 87.5px;
            height: 87.5px;
            border-radius: 100%;
            transform: scale(0);
        }

        #loader .dot:nth-child(1) {
            transform: rotate(45deg);
        }

        #loader .dot:nth-child(1)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.1s;
            background: #00ff80;
        }

        #loader .dot:nth-child(2) {
            transform: rotate(90deg);
        }

        #loader .dot:nth-child(2)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.2s;
            background: #00ffea;
        }

        #loader .dot:nth-child(3) {
            transform: rotate(135deg);
        }

        #loader .dot:nth-child(3)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.3s;
            background: #00aaff;
        }

        #loader .dot:nth-child(4) {
            transform: rotate(180deg);
        }

        #loader .dot:nth-child(4)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.4s;
            background: #0040ff;
        }

        #loader .dot:nth-child(5) {
            transform: rotate(225deg);
        }

        #loader .dot:nth-child(5)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.5s;
            background: #2a00ff;
        }

        #loader .dot:nth-child(6) {
            transform: rotate(270deg);
        }

        #loader .dot:nth-child(6)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.6s;
            background: #9500ff;
        }

        #loader .dot:nth-child(7) {
            transform: rotate(315deg);
        }

        #loader .dot:nth-child(7)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.7s;
            background: magenta;
        }

        #loader .dot:nth-child(8) {
            transform: rotate(360deg);
        }

        #loader .dot:nth-child(8)::before {
            animation: load 0.8s linear infinite;
            animation-delay: 0.8s;
            background: #ff0095;
        }

        #loader .lading {
            position: absolute;
            bottom: -40px;
            left: 0;
            right: 0;
            width: 180px;
            height: 20px;
            text-align: center;
            background-repeat: no-repeat;
            background-position: 50% 50%;
        }

        @keyframes load {
            100% {
                opacity: 0;
                transform: scale(1);
            }
        }

    </style>



<div class="content-wrapper">

    <section class="content-header">
    </section>

    <section class="content">

        <div class="box-body">

            <div class="col-md-12">

                <div class="row">

                    <div class="col-md-10 col-md-offset-1">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <h3 style="text-align: center;">
                                    SISTEMA DE OCURRENCIAS CSP
                                </h3>

                            </div>

                            <div class="panel-body">

                                <div id="loader">

                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>

                                    <div class="lading"></div>

                                </div>

                                <br><br><br><br><br><br><br><br><br>

                                Enviar su incidencia

                                <a href="<?= base_url('mantenimiento/ocurrencias/add') ?>"
                                   style="float:right"
                                   class="btn btn-danger btn-circle btn-lg">

                                    <i class="glyphicon glyphicon-pencil"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>


