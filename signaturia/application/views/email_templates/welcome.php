<?php echo $this->load->view('email_templates/header', '', true); ?>
<div style="padding:30px;margin:0; display: block;vertical-align:top; text-align:left;">
    <p style="padding:0; margin:0; color:#000; font-size:14px; font-weight:400; font-family:Arial, Helvetica, sans-serif; line-height:22px;margin-bottom:20px;">
        <strong>Welcome to signaturia!</strong><br><br>
        Hi there,<br/>
        Thanks for signing up!From now you can use our platform to create amazing email signatures for your team to promote your company with every email sent.
    </p>
    <p style="padding:0; margin:0; color:#000; font-size:14px; font-weight:400; font-family:Arial, Helvetica, sans-serif; line-height:22px;margin-bottom:20px;">
        <a href="<?php echo site_url(); ?>" style="font-size: 14px;color: #fff;background: #44980c;border: none;padding: 15px 25px;font-weight: 500;line-height: 16px;display: inline-block;border-radius: 3px;min-width: 230px;text-transform: uppercase;text-align: center;letter-spacing: 1px;text-decoration:none;">Explore Signaturia</a>
    </p><br><br><br>
    <p style="padding:0; margin:0; color:#000; font-size:14px; font-weight:400; font-family:Arial, Helvetica, sans-serif; line-height:22px;">
        <strong>Kindest regards,<br>The Signaturia team</strong>
    </p>
</div>
<?php echo $this->load->view('email_templates/footer', '', true); ?>