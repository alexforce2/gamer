<form id="registration" class='forms' name="form" method='post' action=''>
    <div class="content-no-auth-reg">
        <h1 align="center">Вход в Личный кабинет</h1>
        <h1 style="padding: 15px; " align="center" >Регистрация</h1>
        <div class="inner">
            <div class="block-forms">
                <table align="center">		                        
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        Как к вам обращаться *<br /><div id="fio-result"></div>
                                        <input type='text' id='fio' name='fio' value="<?= $this->ReturnText('fio'); ?>" />
                                    </td>                        
                                </tr>  
                                <tr>
                                    <td>E-mail *<br /><div id="email-result"></div>
                                        <input type='text' id='email' name='email' value="<?= $this->ReturnText('email'); ?>"  />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Пароль:<br /><div id="pass-result"></div>
                                        <input type='password' id='pass' name='pass' value="<?= $this->ReturnText('pass'); ?>"  />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Подтверждение пароля:<br /><div id="pass-conf-result"></div>
                                        <input type='password' id='pass-conf' class='text' name='pass-conf' value="<?= $this->ReturnText('pass-conf'); ?>" />
                                    </td>
                                </tr>

                            </table>
                        </td>
                        <td style="padding-left: 75px; vertical-align: top;">
                            <table>
                                <tr>
                                    <td id="phone-activation-input">
                                        Телефон (без +7):<br /><div id="phone-result"></div>
                                        <input type='text' id='phone' name='mobile-phone' value="<?= $this->ReturnText('mobile-phone'); ?>" />
                                    </td>                        
                                </tr> 
                                <tr>
                                    <td id="phone-activation-img">
                                        <img src="/skins/img/butt_reg_code.png" onclick="codeActivateRetry()" class="FR butt_code" />
                                    </td>                        
                                </tr> 
                                <tr>
                                    <td>
                                        Код подтверждения:<br /><div id="code-activate-result"></div>
                                        <input type='password' id='code-activate' class='text' name='code-activate' />

                                    </td>
                                </tr>
                                <tr>
                                    <td>                                        
                                        <img src="/skins/img/butt_reg_code_conf.png" onclick="activateAjax()" class="FR butt_code" />
                                    </td>                        
                                </tr>                                 
                            </table>
                        </td>
                    </tr>                    
                </table>

            </div>
            <div class="button">
                <span class="error" ><?= $data['error']; ?></span><br />
                <input type="hidden" name='method' value="registration" />
                <input type="submit" name='form-registration' value='Зарегистрировать' class="submit page1_butt" />
            </div>
            <div style="margin: 20px 45px;">
                <span><a href="/">> Вход</a></span><br />
                <span class="remind"><a href="/restore">> Востановление пароля</a></span>
            </div>
        </div> 
    </div>
</div>
</form>
<div id="modal-reg" class="box-modal" style="width: 330px;"><div class="box-modal_close arcticmodal-close">X</div>
    <b>Завершение регистрации</b><p>На ваш телефон было отправленно <b>бесплатное</b> смс сообщение с кодом активаци.</p>
    <input type="text" class="form-login-input" id="code-reg" placeholder="Код активации"/>
    <a href="javascript:regComplete()" class="btn-login">Регистрация</a><div id="ajax-modal-result"></div></div>


