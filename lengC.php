<?php
require_once "metodos.php";
/* footer.part.php */

/* cambio_pass.view.phtml */
$i_cambio_pass_texto1 = "Introduce tu nueva contraseña: ";
$i_cambio_pass_texto2 = "Confirma tu nueva contraseña: ";
$i_cambio_pass_texto3 = "Mensaje de ayuda para la contraseña";
$i_cambio_pass_boton1 = "Cambiar contraseña";
$i_cambio_pass_error1 = "Las contraseñas no coinciden";

/*chat.view.phtml */
$i_chat_texto1 = "Preguntanos lo que necesites!";
$i_chat_boton1 = "Enviar";

/*  control.view.phtml */
$i_control_tab1 = "Dispositivos";
$i_control_tab2 = "Escenas";
$i_control_tab3 = "Cámaras";
$i_control_texto1 = "No tienes ninguna escena creada!";
$i_control_texto2 = "Crea tu primera escena aqui";
$i_control_boton1 = "Nueva escena";
$i_control_texto3 = "No tienes ninguna cámara configurada!";
$i_control_texto4 = "Contacta con nosotros para ampliar tu plan";
$i_control_boton2 = "Contacta con nosotros";

/* cpanel.view.phtml */
$i_cpanel_tab1 = "Nuevo usuario";
$i_cpanel_tab2 = "Modificar usuario";
$i_cpanel_tab3 = "Chat";
$i_cpanel_form1 = "Nombre de usuario: ";
$i_cpanel_form2 = "E-mail: ";
$i_cpanel_form3 = "IP RaspBerry: ";
$i_cpanel_form4 = "Cantidad de habitaciones: ";
$i_cpanel_form5 = "Habitación: ";
$i_cpanel_form6 = "Cantidad de dispositivos: ";
$i_cpanel_form7 = "Nombre del dispositivo: ";
$i_cpanel_form8 = "Pin: ";
$i_cpanel_form9 = "Temperatura";
$i_cpanel_submit1 = "Registrar usuario";
$i_cpanel_option1 = "-- Elige un usuario --";
$i_cpanel_boton1 = "Enviar";
$i_cpanel_invalid1 = "Es necesario completar este campo";

/* contacto.view.phtml */
$i_contacto_texto1 = "Nombre:";
$i_contacto_texto2 = "E-mail:";
$i_contacto_texto3 = "Motivo de contacto:";
$i_contacto_texto4 = "Otros:";
$i_contacto_option1 = "Duda sobre el precio";
$i_contacto_option2 = "Problemas técnicos";
$i_contacto_option3 = "Consulta sobre el plan contratado";
$i_contacto_option4 = "Duda sobre el precio";
$i_contacto_option5 = "Duda sobre el precio";
$i_contacto_option6 = "Otros...";
$i_contacto_texto5 = "Mensaje:";
$i_contacto_texto6 = "Te contestaremos lo más rápido posible.";
$i_contacto_boton1 = "Enviar consulta";

/* datos_dispositivos.php */
$i_disp_texto1 = "Habitacion: ";
$i_disp_texto2 = "Dispositivo: ";
$i_disp_texto3 = "Veces encendido: ";
$i_disp_texto4 = "Tiempo encendido: ";
$i_disp_texto5 = "Programado para: ";
$i_disp_texto6 = "Inicio: ";
$i_disp_texto7 = "Fin: ";
$i_disp_texto8 = "Temperatura: ";
$i_disp_boton1 = "Programar dispositivo";

/* gest_tiempo.php */
$i_gtiempo_option1 ="Elige la provincia";
$i_gtiempo_option2 ="Elige el municipio";
$i_gtiempo_texto1 ="Humedad: ";
$i_gtiempo_texto2 ="Velocidad del viento: ";

/* gest_user.php */
$i_guser_error1 = "El usuario introducido no existe";
$i_guser_error2 = "Es necesario completar este campo";
$i_guser_texto1 = "Cambiar E-mail: ";
$i_guser_boton1 = "Cambiar e-mail";
$i_guser_texto2 = "Cantidad de habitaciones: ";
$i_guser_texto3 = "Habitacion: ";
$i_guser_texto4 = "Cantidad de dispositivos: ";
$i_guser_texto5 = "Nombre del dispositivo: ";
$i_guser_texto6 = "Pin: ";
$i_guser_texto7 = "¡Atención!";
$i_guser_texto8 = "Va a modificar el email del usuario ";
$i_guser_texto9 = "Va a eliminar el usuario ";
$i_guser_texto10 = "¿Desea continuar?";
$i_guser_texto11 = "Cancelar";
$i_guser_texto12 = "Confirmar";
$i_guser_form7 = "Temperatura";
$i_guser_boton2 = "Modificar usuario";

/* header.part.php */
$i_header_login = "Iniciar sesión";
$i_header_control = "Control";
$i_header_perfil = "Perfil";
$i_header_historial = "Historial";
$i_header_graficas = "Gráficas";
$i_header_panel = "Panel de control";
$i_header_logout = "Cerrar sesión";

/* index.view.phtml */
$i_index_texto1 = "Donde tu casa cobra vida";
$i_index_texto2 = "Contacta con nosotros";
$i_index_texto3 = "El tiempo en tu zona";
$i_index_texto4 = "Consulta el tiempo que hará en tu zona en los próximos 5 dias";
$i_index_select1 = "Elige la comunidad";

/* login.js */
$i_login_texto1 = "Iniciar Sesión";
$i_login_texto2 = "E-mail";
$i_login_texto3 = "Introduce tu e-mail (xxxxx@yyyyy.qqq)";
$i_login_texto4 = "Contraseña";
$i_login_texto5 = "¿Has olvidado tu contraseña?";
$i_login_texto6 = "Iniciar sesion";
$i_login_texto7 = "Cancelar";
$i_login_error1 = "El usuario no existe";
$i_login_error2 = "La contraseña es incorrecta";

/* perfil.view.phtml */
$i_perfil_boton1 = "Cambiar foto";
$i_perfil_texto1 = "Nombre: ";
$i_perfil_texto2 = "E-mail: ";
$i_perfil_texto3 = "Dispositivos: ";
$i_perfil_texto4 = "Escenas: ";
$i_perfil_boton2 = "Comprobar log";
$i_perfil_tab1 = "Dispositivos";
$i_perfil_tab2 = "Programas";
$i_perfil_tab3 = "Escenas";
$i_perfil_popover1 = "Habitación: ";
$i_perfil_popover2 = "Veces usado: ";
$i_perfil_popover3 = "Tiempo encendido: ";
$i_perfil_popover4 = "Pin: ";
$i_perfil_texto5 = "Cámaras";
$i_perfil_texto6 = "Programas";
$i_perfil_popover5 = "Dispositivo: ";
$i_perfil_popover6 = "Hora inicio: ";
$i_perfil_popover7 = "Hora fin: ";
$i_perfil_popover8 = "Fecha inicio: ";
$i_perfil_popover9 = "Fecha fin: ";
$i_perfil_texto7 = "No tienes ningún dispositivo programado";
$i_perfil_texto8 = "Programa tu primer dispositivo";
$i_perfil_texto9 = "Escenas";
$i_perfil_texto10 = "No tienes ninguna escena programada";
$i_perfil_texto11 = "Crea tu primera escena";

/* recuperar.view.phtml */
$i_recuperar_texto1 = "Introduce tu e-mail:";
$i_recuperar_texto2 = "Se te enviara un correo con instrucciones para cambiar la contraseña";
$i_recuperar_boton1 = "Enviar e-mail";

?>