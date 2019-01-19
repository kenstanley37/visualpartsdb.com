<a href="/#aboutvpd">About VPD</a>
<a href="/#member">Membership</a>
<?php if(isset($_SESSION['user_id'])){ echo '<a href="#" class="navLinks">My Searches</a>';} ?> 
<?php if(isset($_SESSION['user_id'])){ echo '<a href="#" class="navLinks">My Export List</a>';} ?> 