<style>
.nav>li>a{
  padding: 5px 10px;
}
.odontograma-navegacion{
  list-style: none;
  padding: 0;
  margin: 0;
  margin-top: 10px;
  cursor: pointer;
}

.odontograma-navegacion a,.dropdown-menu>li>a{
  color: #333;
}

.odontograma-navegacion>li{
  padding: 5px 0;
  background: none;
}

.odontograma-navegacion>li button{
  border: none;
  background: none;
  padding: 0;
}

.odontograma-navegacion .dropdown-menu{
  padding: 0;
}

.odontograma-navegacion .dropdown-menu li{
  padding: 0;
}

.odontograma-navegacion .dropdown-menu li>a>i.buen{
  color: #00D900;
}
.odontograma-navegacion .dropdown-menu li>a>i.mal{
  color: #FF0000;
}

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: -160px;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: left;
    width: 0;
    height: 0;
    left: 10px;
    top: 3px;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 5px 5px 0;
    border-right-color: #333;
    margin-top: 5px;
    margin-right: -10px;
    position: absolute;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #333;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
</style>
<div id="odontograma-navegacion">
  <button id="CapturarOdontograma" class="btn btn-flat btn-lg btn-success btn-block">Capturar Odont.</button>
  <br>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#AI" aria-controls="AI" role="tab" data-toggle="tab">A-I</a></li>
    <li role="presentation"><a href="#LP" aria-controls="LP" role="tab" data-toggle="tab">L-P</a></li>
    <li role="presentation"><a href="#RT" aria-controls="RT" role="tab" data-toggle="tab">R-T</a></li>
    <li role="presentation"><a href="#Detalle" aria-controls="Detalle" role="tab" data-toggle="tab">Detalle</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="AI">
      <ul class="odontograma-navegacion">
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/AparatoOrtoIcono.png">
          <a class="dropdown-toggle rango nombreHallazgo" id="dropdownOortoFijo" data-hallazgo="1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Aparato Orto. Fijo
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownOortoFijo">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/AparatoOrtodonticoRemovibleIcono.png">
          <a class="dropdown-toggle rango nombreHallazgo" data-hallazgo="2" id="dropdownOrtoRemovible" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Aparato Orto. Removible
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownOrtoRemovible">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>

          <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgariosa.png">
          <a class="dropdown-toggle nombreHallazgo hallazgoMarcar" data-marcaclass="lesionCaries" id="dropdownLesionCaries" data-hallazgo="4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          Caries Dental
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownLesionCaries">
            <li><a href="#" class="siglas odontograma-item" data-sigla="MB">MB</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="CE">CE</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="CD">CD</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="CDP">CDP</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/CoronaDefinitivaIcono.png">
          <a class="dropdown-toggle" id="dropdownCoronaDefinitiva" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Corona
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="3" data-sigla="CM" href="#"><b>CM:</b> Corona Metálica</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>

            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="3" data-sigla="CF"  href="#"><b>CF:</b> Corona Fenestrada</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="3" data-sigla="CMC" href="#"><b>CMC:</b> Corona Metal Cerámica</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="3" data-sigla="CV" href="#"><b>CV:</b> Corona Veneer</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="3" data-sigla="CJ" href="#"><b>CJ:</b> Corona Jacket</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo"  class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/Coronatemp.png">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownCoronaTemporal" data-hallazgo="37" data-sigla="CT" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Corona Temporal
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownCoronaTemporal">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/interrogacion.png">
          <a class="dropdown-toggle" id="dropdownCoronaDefinitiva" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Defectos de Desarrollo del Esmalte
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="5" data-sigla="HP" href="#"><b>HP:</b> Hipoplasia</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="5" data-sigla="HM"  href="#"><b>HM:</b> Hipo Mineralización</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="5" data-sigla="O" href="#"><b>O:</b> Opacidades del Esmalte</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="5" data-sigla="D" href="#"><b>D:</b> Decoloración del Esmalte</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="5" data-sigla="Fluorosis" href="#"><b>Fluorosis:</b> Fluorosis</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgdiastema.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="17">
            Diastema
          </a>
        </li>
        
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgendtotal.png">
          <a class="dropdown-toggle rango nombreHallazgo odontograma-item" data-hallazgo="13">
            Edentulo Total
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/resized_imgdiente.PNG">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownMunon" data-hallazgo="30"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Espigo Muñon
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMunon">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/resized_imgdiente.PNG">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="8">
            Fosas y Fisuras Profundas
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgfractura.png">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownFractura" data-hallazgo="7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Fractura
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownFractura">

            <li><a href="#" class="odontograma-item" data-categoria="Coronal"> <img src="<?= base_url() ?>vendor/odontograma/images/imgfracturacorona.png"> Fractura Coronal</a></li>
            <li><a href="#" class="odontograma-item" data-categoria="Incisal"><img src="<?= base_url() ?>vendor/odontograma/images/imgfracturaincisal.png"> Fractura Incisal</a></li>
            <li><a href="#" class="odontograma-item" data-categoria="Raiz y Coronal"><img src="<?= base_url() ?>vendor/odontograma/images/imgfacturaiz.png"> Fractura Raiz y Coronal</a></li>
          </ul>
        </li>

        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/resized_imgdiente.PNG">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="24">
            Fusión
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/interrogacion.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="25">
            Geminasión
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/resized_imgiroversion.PNG">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownGiroversion" data-hallazgo="18" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Giroversión
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownGiroversion">
            <li><a href="#" class="odontograma-item" data-categoria="Distal">Giroversion Distal</a></li>
            <li><a href="#" class="odontograma-item" data-categoria="Mesial">Giroversion Mesial</a></li>
          </ul>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgimpactacion.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="26">
            Impactación
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgimplant.png">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownImplanteDental" data-hallazgo="31"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Implante Dental
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownImplanteDental">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div role="tabpanel" class="tab-pane" id="LP">
      <ul class="odontograma-navegacion">
      
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgmacrodoncia.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="22">
            Macrodoncia
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgmicrodoncia.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="23">
            Microdoncia
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgmovilidadpat.png">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownMovilidadPatologica" data-hallazgo="29" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Movilidad Patológica
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMovilidadPatologica">
            <li><a href="#" class="siglas odontograma-item" data-sigla="M1">M1</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="M2">M2</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="M3">M3</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="M4">M4</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="M5">M5</a></li>
          </ul>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/resized_imgpiezausente.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="9">
            Pieza Dentaria Ausente
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/resized_imgpiezaocto.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="21" data-sigla="E">
            Pieza Dentaria Ectópica
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgdienteclavija.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="20">
            Pieza Dentaria en Clavija
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgdienterupcion.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="10">
            Pieza Dentaria en Erupción
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgdientextruido.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="15">
            Pieza Dentaria  Extruida
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgdientintruido.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="16">
            Pieza Dentaria Intruida
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgpupernumerario.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="14">
            Pieza Dentaria Supernumeraria
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/interrogacion.png">
          <a class="dropdown-toggle nombreHallazgo" id="dropdownPosicionDentaria" data-hallazgo="19" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Posicion Dentaria
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownPosicionDentaria">
            <li><a href="#" class="siglas odontograma-item" data-sigla="M"><b>M:</b> Mesializado</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="D"><b>D:</b> Distalizado</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="V"><b>V:</b> Vetibularizado</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="P"><b>P:</b> Palatinizado</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="L"><b>L:</b> Lingualizado</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgProtesisFija.png">
          <a class="dropdown-toggle rango nombreHallazgo" data-hallazgo="32" id="dropdownProtesisFija" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Prótesis Fija
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownProtesisFija">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgprotesisremovible.png">
          <a class="dropdown-toggle rango nombreHallazgo" data-hallazgo="33" id="dropdownProtesisRemovible" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Prótesis Removible
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownProtesisRemovible">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgprotesistotal.png">
          <a class="dropdown-toggle rango nombreHallazgo" data-hallazgo="34" id="dropdownProtesisTotal" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Prótesis Total
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownProtesisTotal">
            <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
            <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div role="tabpanel" class="tab-pane" id="RT">
      <ul class="odontograma-navegacion">
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgremanenteradicular.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="28">
            Remanente Radicular
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgrestauraciondefinitiva.png">
          <a class="dropdown-toggle nombreHallazgo hallazgoMarcar hallazgoMarcarEstado" data-marcaclass="restauracionDefinitiva" id="dropdownPosicionDentaria" data-hallazgo="11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Restauración Definitiva
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownPosicionDentaria">
            <li><a href="#" class="siglas odontograma-item" data-sigla="AM"><b>AM:</b> Amalgama Dental</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="R"><b>R:</b> Resina</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="IV"><b>IV:</b> Ionómero de Vidrio</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="IM"><b>IM:</b> Incrutacion Metálica</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="IE"><b>IE:</b> Incrustación Estética</a></li>
            <li><a href="#" class="siglas odontograma-item" data-sigla="C"><b>C:</b> Carilla Estética</a></li>
          </ul>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgrestauraciontemporal.png">
          <a data-marcaclass="restauracionTemporal" class="dropdown-toggle nombreHallazgo hallazgoMarcar odontograma-item" data-hallazgo="12">
            Restauración Temporal
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgsellantes.png">
          <a data-marcaclass="sellantes" class="dropdown-toggle nombreHallazgo hallazgoMarcar hallazgoMarcarEstado odontograma-item" data-hallazgo="6">
            Sellantes
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/interrogacion.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="27">
            Superficie Desgastada
          </a>
        </li>
        <li>
          <img src="<?= base_url() ?>vendor/odontograma/images/imgtransposicion.png">
          <a class="dropdown-toggle nombreHallazgo odontograma-item" data-hallazgo="36">
            Transposición
          </a>
        </li>
        <li class="dropdown">
          <img src="<?= base_url() ?>vendor/odontograma/images/imgtratamientopulpal.png">
          <a class="dropdown-toggle" id="dropdownTratamientoPulpar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Tratamiento Pulpar
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="35" data-sigla="TC" href="#"><b>TC:</b> Tratamiento de conductos</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="35" data-sigla="PC"  href="#"><b>PC:</b> Pulpectomía</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a tabindex="-1" class="nombreHallazgo" data-hallazgo="35" data-sigla="PP" href="#"><b>PP:</b> Pulpotomía</a>
              <ul class="dropdown-menu">
                <li><a href="#" data-estado="bueno" class="odontograma-item"><i class="fa fa-thumbs-o-up buen"></i>Buen Estado</a></li>
                <li><a href="#" data-estado="malo" class="odontograma-item"><i class="fa fa-thumbs-o-down mal"></i>Mal Estado</a></li>
              </ul>
            </li>

          </ul>
        </li>
      </ul>
    </div>
    <div role="tabpanel" class="tab-pane" id="Detalle">
      <br>
      
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Detalle</label>
            <textarea id="TextAreaDetalle" name="detalle" class="form-control" rows="10"><?= $paciente->detalleodontograma_pac ?></textarea>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <button type="button" id="GuardarDetalle" class="btn btn-success btn-md">Guardar Detalle</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <button id="BotonNombreSeleccionado" class="btn btn-flat btn-block" style="display: none"></button>
  <button id="BotonSeleccion" class="btn btn-flat btn-lg btn-info btn-block">Seleccione</button>
  <br>
</div>
