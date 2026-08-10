<div class="member_in_box">
   <div class="wrap">
      {if $_login_google eq '1'}		
      <div class="signin_social_box">
         <big class="text-center">Đăng nhập với tài khoản</big>			
         <ul class="signin_box_list">
            {if $_login_facebook eq '1'}			
            <li><a title="Đăng nhập qua Facebook"{if $deviceType eq 'phone'} href="{$facebookLoginUrl}"{/if} class="signin-via-facebook disabled{if $deviceType ne 'phone'} clickable{/if} login-network" mod_page="{$mod}" act_page="{$act}" rel="_FACEBOOK"></a></li>
            {/if}			
			{if $_login_google eq '1'}				
            <li><a title="Đăng nhập qua Google"{if $deviceType eq 'phone'} href="{$googleLoginUrl}"{/if} class="signin-via-google{if $deviceType ne 'phone'} clickable{/if} login-network" mod_page="{$mod}" act_page="{$act}" rel="_GOOGLE"></a></li>
            {/if}			
         </ul>
      </div>
      {/if}  
   </div>
</div>