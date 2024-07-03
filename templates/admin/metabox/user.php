<?php

defined('ABSPATH') || exit;

global $rz_user;

$verified = get_user_meta( $rz_user->ID, 'rz_verified', true );
$role = get_user_meta( $rz_user->ID, 'rz_role', true );

?>

<table class="form-table" role="presentation">
	<tbody>
    	<tr class="show-admin-bar user-admin-bar-front-wrap">
    		<th scope="row">
                <?php esc_html_e( 'Verified user', 'routiz' ); ?>
            </th>
    		<td>
    			<label for="rz_verified">
    				<input name="rz_verified" type="checkbox" id="rz_verified" value="1"<?php echo $verified ? ' checked="checked"' : ''; ?>>
    				<?php esc_html_e( 'Yes', 'routiz' ); ?>
                </label>
    		</td>
    	</tr>
    	<tr class="show-admin-bar user-admin-bar-front-wrap">
    		<th scope="row">
                <?php esc_html_e( 'User role', 'routiz' ); ?>
            </th>
    		<td>
				<select name="rz_role" id="rz_role">
					<option value="customer"<?php echo $role == 'customer' ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Customer', 'routiz' ); ?></option>
					<option value="business"<?php echo $role == 'business' ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Business', 'routiz' ); ?></option>
				</select>
    		</td>
    	</tr>
    </tbody>
</table>
