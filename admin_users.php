<?php
session_start();

if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}


include("header.php") ?>


    <div class="container">
      
    <div class="row">
        <div class="span12">
          <h2>Users</h2>
        </div>
    </div>
  
    <div class="row">
        <div class="span2" style="text-align:left;padding-bottom:5px;padding-left:5px;">
          <a href="#" class="btn btn-add" data-toggle="modal">Add user(s)</a>
        </div>
    </div>
      
      <div class="row">
        <div class="span12">
        		<div id="tbl_exps_processing" class="dataTables_processing">Processing...</div>
                <div class="alert alert-error" id="div_msg" style="display:none">Loading ...</div>
                <table id="tbl_users" class="table table-bordered table-striped table-condensed" style="display:none">
                    <thead>
                        <tr>
                            <th>Login</th>
                            <th>FirstName</th>
                            <th>LastName</th>
                            <th>Email</th>
                            <th>Creation date</th>
                            <th width='50px'>State</th>
                            <th width='50px'>Role</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
        </div>

      </div>

        <div id="add_modal" class="modal hide fade" style="margin-left:-300px;">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">X</a>
              <h3>Add user(s)</h3>
              
            </div>
           <div class="modal-body" style="max-height:690px;">
               <div class="alert alert-error" id="div_error_add" style="display:none"></div>

               <div class="tabbable">
               	<ul class="nav nav-tabs">
               		<li class="active"><a href="#tab_SA" data-toggle="tab">Single Account Creation</a></li>
               		<li><a href="#tab_MA" data-toggle="tab">Multiple Accounts Creation</a></li>
               	</ul>

				<div class="tab-content">				
				
               		<!-- PANEL FOR SINGLE ACCOUNT CREATION FORM -->
               		
					<div class="tab-pane active" id="tab_SA">
					<form class="well form-horizontal" id="form_add_SA">
               

		              <div class="control-group">
		                <label class="control-label" for="txt_firstname_SA">First Name:</label>
		                <div class="controls">
		                    <input id="txt_firstname_SA" type="text" class="input-xlarge" required="required"/>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_lastname_SA">Last Name:</label>
		                <div class="controls">
		                    <input id="txt_lastname_SA" type="text" class="input-xlarge" required="required"/>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_email_SA">Email:</label>
		                <div class="controls">
		                    <input id="txt_email_SA" type="email" class="input-xlarge" required="required"/>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_structure_SA">Structure:</label>
		                <div class="controls">
		                    <input id="txt_structure_SA" type="text" class="input-xlarge" required="required" />
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_city_SA">City:</label>
		                <div class="controls">
		                    <input id="txt_city_SA" type="text" class="input-xlarge" required="required" />
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_country_SA">Country:</label>
			            <div class="controls">
							<select id="txt_country_SA" required="required">
			            		<option value="">Country...</option>
								<option value="Afganistan">Afghanistan</option>
								<option value="Albania">Albania</option>
								<option value="Algeria">Algeria</option>
								<option value="American Samoa">American Samoa</option>
								<option value="Andorra">Andorra</option>
								<option value="Angola">Angola</option>
								<option value="Anguilla">Anguilla</option>
								<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
								<option value="Argentina">Argentina</option>
								<option value="Armenia">Armenia</option>
								<option value="Aruba">Aruba</option>
								<option value="Australia">Australia</option>
								<option value="Austria">Austria</option>
								<option value="Azerbaijan">Azerbaijan</option>
								<option value="Bahamas">Bahamas</option>
								<option value="Bahrain">Bahrain</option>
								<option value="Bangladesh">Bangladesh</option>
								<option value="Barbados">Barbados</option>
								<option value="Belarus">Belarus</option>
								<option value="Belgium">Belgium</option>
								<option value="Belize">Belize</option>
								<option value="Benin">Benin</option>
								<option value="Bermuda">Bermuda</option>
								<option value="Bhutan">Bhutan</option>
								<option value="Bolivia">Bolivia</option>
								<option value="Bonaire">Bonaire</option>
								<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
								<option value="Botswana">Botswana</option>
								<option value="Brazil">Brazil</option>
								<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
								<option value="Brunei">Brunei</option>
								<option value="Bulgaria">Bulgaria</option>
								<option value="Burkina Faso">Burkina Faso</option>
								<option value="Burundi">Burundi</option>
								<option value="Cambodia">Cambodia</option>
								<option value="Cameroon">Cameroon</option>
								<option value="Canada">Canada</option>
								<option value="Canary Islands">Canary Islands</option>
								<option value="Cape Verde">Cape Verde</option>
								<option value="Cayman Islands">Cayman Islands</option>
								<option value="Central African Republic">Central African Republic</option>
								<option value="Chad">Chad</option>
								<option value="Channel Islands">Channel Islands</option>
								<option value="Chile">Chile</option>
								<option value="China">China</option>
								<option value="Christmas Island">Christmas Island</option>
								<option value="Cocos Island">Cocos Island</option>
								<option value="Colombia">Colombia</option>
								<option value="Comoros">Comoros</option>
								<option value="Congo">Congo</option>
								<option value="Cook Islands">Cook Islands</option>
								<option value="Costa Rica">Costa Rica</option>
								<option value="Cote DIvoire">Cote D'Ivoire</option>
								<option value="Croatia">Croatia</option>
								<option value="Cuba">Cuba</option>
								<option value="Curaco">Curacao</option>
								<option value="Cyprus">Cyprus</option>
								<option value="Czech Republic">Czech Republic</option>
								<option value="Denmark">Denmark</option>
								<option value="Djibouti">Djibouti</option>
								<option value="Dominica">Dominica</option>
								<option value="Dominican Republic">Dominican Republic</option>
								<option value="East Timor">East Timor</option>
								<option value="Ecuador">Ecuador</option>
								<option value="Egypt">Egypt</option>
								<option value="El Salvador">El Salvador</option>
								<option value="Equatorial Guinea">Equatorial Guinea</option>
								<option value="Eritrea">Eritrea</option>
								<option value="Estonia">Estonia</option>
								<option value="Ethiopia">Ethiopia</option>
								<option value="Falkland Islands">Falkland Islands</option>
								<option value="Faroe Islands">Faroe Islands</option>
								<option value="Fiji">Fiji</option>
								<option value="Finland">Finland</option>
								<option value="France">France</option>
								<option value="French Guiana">French Guiana</option>
								<option value="French Polynesia">French Polynesia</option>
								<option value="French Southern Ter">French Southern Ter</option>
								<option value="Gabon">Gabon</option>
								<option value="Gambia">Gambia</option>
								<option value="Georgia">Georgia</option>
								<option value="Germany">Germany</option>
								<option value="Ghana">Ghana</option>
								<option value="Gibraltar">Gibraltar</option>
								<option value="Great Britain">Great Britain</option>
								<option value="Greece">Greece</option>
								<option value="Greenland">Greenland</option>
								<option value="Grenada">Grenada</option>
								<option value="Guadeloupe">Guadeloupe</option>
								<option value="Guam">Guam</option>
								<option value="Guatemala">Guatemala</option>
								<option value="Guinea">Guinea</option>
								<option value="Guyana">Guyana</option>
								<option value="Haiti">Haiti</option>
								<option value="Hawaii">Hawaii</option>
								<option value="Honduras">Honduras</option>
								<option value="Hong Kong">Hong Kong</option>
								<option value="Hungary">Hungary</option>
								<option value="Iceland">Iceland</option>
								<option value="India">India</option>
								<option value="Indonesia">Indonesia</option>
								<option value="Iran">Iran</option>
								<option value="Iraq">Iraq</option>
								<option value="Ireland">Ireland</option>
								<option value="Isle of Man">Isle of Man</option>
								<option value="Israel">Israel</option>
								<option value="Italy">Italy</option>
								<option value="Jamaica">Jamaica</option>
								<option value="Japan">Japan</option>
								<option value="Jordan">Jordan</option>
								<option value="Kazakhstan">Kazakhstan</option>
								<option value="Kenya">Kenya</option>
								<option value="Kiribati">Kiribati</option>
								<option value="Korea North">Korea North</option>
								<option value="Korea Sout">Korea South</option>
								<option value="Kuwait">Kuwait</option>
								<option value="Kyrgyzstan">Kyrgyzstan</option>
								<option value="Laos">Laos</option>
								<option value="Latvia">Latvia</option>
								<option value="Lebanon">Lebanon</option>
								<option value="Lesotho">Lesotho</option>
								<option value="Liberia">Liberia</option>
								<option value="Libya">Libya</option>
								<option value="Liechtenstein">Liechtenstein</option>
								<option value="Lithuania">Lithuania</option>
								<option value="Luxembourg">Luxembourg</option>
								<option value="Macau">Macau</option>
								<option value="Macedonia">Macedonia</option>
								<option value="Madagascar">Madagascar</option>
								<option value="Malaysia">Malaysia</option>
								<option value="Malawi">Malawi</option>
								<option value="Maldives">Maldives</option>
								<option value="Mali">Mali</option>
								<option value="Malta">Malta</option>
								<option value="Marshall Islands">Marshall Islands</option>
								<option value="Martinique">Martinique</option>
								<option value="Mauritania">Mauritania</option>
								<option value="Mauritius">Mauritius</option>
								<option value="Mayotte">Mayotte</option>
								<option value="Mexico">Mexico</option>
								<option value="Midway Islands">Midway Islands</option>
								<option value="Moldova">Moldova</option>
								<option value="Monaco">Monaco</option>
								<option value="Mongolia">Mongolia</option>
								<option value="Montserrat">Montserrat</option>
								<option value="Morocco">Morocco</option>
								<option value="Mozambique">Mozambique</option>
								<option value="Myanmar">Myanmar</option>
								<option value="Nambia">Nambia</option>
								<option value="Nauru">Nauru</option>
								<option value="Nepal">Nepal</option>
								<option value="Netherland Antilles">Netherland Antilles</option>
								<option value="Netherlands">Netherlands (Holland, Europe)</option>
								<option value="Nevis">Nevis</option>
								<option value="New Caledonia">New Caledonia</option>
								<option value="New Zealand">New Zealand</option>
								<option value="Nicaragua">Nicaragua</option>
								<option value="Niger">Niger</option>
								<option value="Nigeria">Nigeria</option>
								<option value="Niue">Niue</option>
								<option value="Norfolk Island">Norfolk Island</option>
								<option value="Norway">Norway</option>
								<option value="Oman">Oman</option>
								<option value="Pakistan">Pakistan</option>
								<option value="Palau Island">Palau Island</option>
								<option value="Palestine">Palestine</option>
								<option value="Panama">Panama</option>
								<option value="Papua New Guinea">Papua New Guinea</option>
								<option value="Paraguay">Paraguay</option>
								<option value="Peru">Peru</option>
								<option value="Phillipines">Philippines</option>
								<option value="Pitcairn Island">Pitcairn Island</option>
								<option value="Poland">Poland</option>
								<option value="Portugal">Portugal</option>
								<option value="Puerto Rico">Puerto Rico</option>
								<option value="Qatar">Qatar</option>
								<option value="Republic of Montenegro">Republic of Montenegro</option>
								<option value="Republic of Serbia">Republic of Serbia</option>
								<option value="Reunion">Reunion</option>
								<option value="Romania">Romania</option>
								<option value="Russia">Russia</option>
								<option value="Rwanda">Rwanda</option>
								<option value="St Barthelemy">St Barthelemy</option>
								<option value="St Eustatius">St Eustatius</option>
								<option value="St Helena">St Helena</option>
								<option value="St Kitts-Nevis">St Kitts-Nevis</option>
								<option value="St Lucia">St Lucia</option>
								<option value="St Maarten">St Maarten</option>
								<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
								<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
								<option value="Saipan">Saipan</option>
								<option value="Samoa">Samoa</option>
								<option value="Samoa American">Samoa American</option>
								<option value="San Marino">San Marino</option>
								<option value="Sao Tome & Principe">Sao Tome &amp; Principe</option>
								<option value="Saudi Arabia">Saudi Arabia</option>
								<option value="Senegal">Senegal</option>
								<option value="Seychelles">Seychelles</option>
								<option value="Sierra Leone">Sierra Leone</option>
								<option value="Singapore">Singapore</option>
								<option value="Slovakia">Slovakia</option>
								<option value="Slovenia">Slovenia</option>
								<option value="Solomon Islands">Solomon Islands</option>
								<option value="Somalia">Somalia</option>
								<option value="South Africa">South Africa</option>
								<option value="Spain">Spain</option>
								<option value="Sri Lanka">Sri Lanka</option>
								<option value="Sudan">Sudan</option>
								<option value="Suriname">Suriname</option>
								<option value="Swaziland">Swaziland</option>
								<option value="Sweden">Sweden</option>
								<option value="Switzerland">Switzerland</option>
								<option value="Syria">Syria</option>
								<option value="Tahiti">Tahiti</option>
								<option value="Taiwan">Taiwan</option>
								<option value="Tajikistan">Tajikistan</option>
								<option value="Tanzania">Tanzania</option>
								<option value="Thailand">Thailand</option>
								<option value="Togo">Togo</option>
								<option value="Tokelau">Tokelau</option>
								<option value="Tonga">Tonga</option>
								<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
								<option value="Tunisia">Tunisia</option>
								<option value="Turkey">Turkey</option>
								<option value="Turkmenistan">Turkmenistan</option>
								<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
								<option value="Tuvalu">Tuvalu</option>
								<option value="Uganda">Uganda</option>
								<option value="Ukraine">Ukraine</option>
								<option value="United Arab Erimates">United Arab Emirates</option>
								<option value="United Kingdom">United Kingdom</option>
								<option value="United States of America">United States of America</option>
								<option value="Uraguay">Uruguay</option>
								<option value="Uzbekistan">Uzbekistan</option>
								<option value="Vanuatu">Vanuatu</option>
								<option value="Vatican City State">Vatican City State</option>
								<option value="Venezuela">Venezuela</option>
								<option value="Vietnam">Vietnam</option>
								<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
								<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
								<option value="Wake Island">Wake Island</option>
								<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
								<option value="Yemen">Yemen</option>
								<option value="Zaire">Zaire</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
			              	</select>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_sshkey_SA">SSH Key:</label>
		                <div class="controls">
		                    <textarea id="txt_sshkey_SA" class="input-xlarge" rows="3" required="required"></textarea>
		                </div>
		              </div>
		              
		               <div class="control-group">
		                <label class="control-label" for="txt_motivation_SA">Motivation:</label>
		                <div class="controls">
		                    <textarea id="txt_motivation_SA" class="input-xlarge" rows="3" required="required"></textarea>
		                </div>
		              </div>
		              
                   	  <button id="btn_add_SA" class="btn btn-primary" type="submit">Add</button>
		            </form>
		            </div>				
				
               		<!-- PANEL FOR MULTIPLE ACCOUNTS CREATION FORM -->
               		
					<div class="tab-pane" id="tab_MA">
					<form class="well form-horizontal" id="form_add_MA">
               
		              <div class="control-group">
		                <label class="control-label" for="txt_howmany_MA">How many accounts:</label>
		                <div class="controls">
		                    <input id="txt_howmany_MA" type="text" class="input-xlarge" required="required" value="1"/>
		                </div>
		              </div>
		              
		              <div class="control-group">
		                <label class="control-label" for="txt_login_MA">Login:</label>
		                <div class="controls">
		                    <input id="txt_login_MA" type="text" class="input-xlarge" required="required"/>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_event_MA">Event:</label>
		                <div class="controls">
		                    <input id="txt_event_MA" type="text" class="input-xlarge" required="required"/>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_structure_MA">Structure:</label>
		                <div class="controls">
		                    <input id="txt_structure_MA" type="text" class="input-xlarge" required="required" />
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_city_MA">City:</label>
		                <div class="controls">
		                    <input id="txt_city_MA" type="text" class="input-xlarge" required="required" />
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_country_MA">Country:</label>
			            <div class="controls">
							<select id="txt_country_MA" required="required">
			            		<option value="">Country...</option>
								<option value="Afganistan">Afghanistan</option>
								<option value="Albania">Albania</option>
								<option value="Algeria">Algeria</option>
								<option value="American Samoa">American Samoa</option>
								<option value="Andorra">Andorra</option>
								<option value="Angola">Angola</option>
								<option value="Anguilla">Anguilla</option>
								<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
								<option value="Argentina">Argentina</option>
								<option value="Armenia">Armenia</option>
								<option value="Aruba">Aruba</option>
								<option value="Australia">Australia</option>
								<option value="Austria">Austria</option>
								<option value="Azerbaijan">Azerbaijan</option>
								<option value="Bahamas">Bahamas</option>
								<option value="Bahrain">Bahrain</option>
								<option value="Bangladesh">Bangladesh</option>
								<option value="Barbados">Barbados</option>
								<option value="Belarus">Belarus</option>
								<option value="Belgium">Belgium</option>
								<option value="Belize">Belize</option>
								<option value="Benin">Benin</option>
								<option value="Bermuda">Bermuda</option>
								<option value="Bhutan">Bhutan</option>
								<option value="Bolivia">Bolivia</option>
								<option value="Bonaire">Bonaire</option>
								<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
								<option value="Botswana">Botswana</option>
								<option value="Brazil">Brazil</option>
								<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
								<option value="Brunei">Brunei</option>
								<option value="Bulgaria">Bulgaria</option>
								<option value="Burkina Faso">Burkina Faso</option>
								<option value="Burundi">Burundi</option>
								<option value="Cambodia">Cambodia</option>
								<option value="Cameroon">Cameroon</option>
								<option value="Canada">Canada</option>
								<option value="Canary Islands">Canary Islands</option>
								<option value="Cape Verde">Cape Verde</option>
								<option value="Cayman Islands">Cayman Islands</option>
								<option value="Central African Republic">Central African Republic</option>
								<option value="Chad">Chad</option>
								<option value="Channel Islands">Channel Islands</option>
								<option value="Chile">Chile</option>
								<option value="China">China</option>
								<option value="Christmas Island">Christmas Island</option>
								<option value="Cocos Island">Cocos Island</option>
								<option value="Colombia">Colombia</option>
								<option value="Comoros">Comoros</option>
								<option value="Congo">Congo</option>
								<option value="Cook Islands">Cook Islands</option>
								<option value="Costa Rica">Costa Rica</option>
								<option value="Cote DIvoire">Cote D'Ivoire</option>
								<option value="Croatia">Croatia</option>
								<option value="Cuba">Cuba</option>
								<option value="Curaco">Curacao</option>
								<option value="Cyprus">Cyprus</option>
								<option value="Czech Republic">Czech Republic</option>
								<option value="Denmark">Denmark</option>
								<option value="Djibouti">Djibouti</option>
								<option value="Dominica">Dominica</option>
								<option value="Dominican Republic">Dominican Republic</option>
								<option value="East Timor">East Timor</option>
								<option value="Ecuador">Ecuador</option>
								<option value="Egypt">Egypt</option>
								<option value="El Salvador">El Salvador</option>
								<option value="Equatorial Guinea">Equatorial Guinea</option>
								<option value="Eritrea">Eritrea</option>
								<option value="Estonia">Estonia</option>
								<option value="Ethiopia">Ethiopia</option>
								<option value="Falkland Islands">Falkland Islands</option>
								<option value="Faroe Islands">Faroe Islands</option>
								<option value="Fiji">Fiji</option>
								<option value="Finland">Finland</option>
								<option value="France">France</option>
								<option value="French Guiana">French Guiana</option>
								<option value="French Polynesia">French Polynesia</option>
								<option value="French Southern Ter">French Southern Ter</option>
								<option value="Gabon">Gabon</option>
								<option value="Gambia">Gambia</option>
								<option value="Georgia">Georgia</option>
								<option value="Germany">Germany</option>
								<option value="Ghana">Ghana</option>
								<option value="Gibraltar">Gibraltar</option>
								<option value="Great Britain">Great Britain</option>
								<option value="Greece">Greece</option>
								<option value="Greenland">Greenland</option>
								<option value="Grenada">Grenada</option>
								<option value="Guadeloupe">Guadeloupe</option>
								<option value="Guam">Guam</option>
								<option value="Guatemala">Guatemala</option>
								<option value="Guinea">Guinea</option>
								<option value="Guyana">Guyana</option>
								<option value="Haiti">Haiti</option>
								<option value="Hawaii">Hawaii</option>
								<option value="Honduras">Honduras</option>
								<option value="Hong Kong">Hong Kong</option>
								<option value="Hungary">Hungary</option>
								<option value="Iceland">Iceland</option>
								<option value="India">India</option>
								<option value="Indonesia">Indonesia</option>
								<option value="Iran">Iran</option>
								<option value="Iraq">Iraq</option>
								<option value="Ireland">Ireland</option>
								<option value="Isle of Man">Isle of Man</option>
								<option value="Israel">Israel</option>
								<option value="Italy">Italy</option>
								<option value="Jamaica">Jamaica</option>
								<option value="Japan">Japan</option>
								<option value="Jordan">Jordan</option>
								<option value="Kazakhstan">Kazakhstan</option>
								<option value="Kenya">Kenya</option>
								<option value="Kiribati">Kiribati</option>
								<option value="Korea North">Korea North</option>
								<option value="Korea Sout">Korea South</option>
								<option value="Kuwait">Kuwait</option>
								<option value="Kyrgyzstan">Kyrgyzstan</option>
								<option value="Laos">Laos</option>
								<option value="Latvia">Latvia</option>
								<option value="Lebanon">Lebanon</option>
								<option value="Lesotho">Lesotho</option>
								<option value="Liberia">Liberia</option>
								<option value="Libya">Libya</option>
								<option value="Liechtenstein">Liechtenstein</option>
								<option value="Lithuania">Lithuania</option>
								<option value="Luxembourg">Luxembourg</option>
								<option value="Macau">Macau</option>
								<option value="Macedonia">Macedonia</option>
								<option value="Madagascar">Madagascar</option>
								<option value="Malaysia">Malaysia</option>
								<option value="Malawi">Malawi</option>
								<option value="Maldives">Maldives</option>
								<option value="Mali">Mali</option>
								<option value="Malta">Malta</option>
								<option value="Marshall Islands">Marshall Islands</option>
								<option value="Martinique">Martinique</option>
								<option value="Mauritania">Mauritania</option>
								<option value="Mauritius">Mauritius</option>
								<option value="Mayotte">Mayotte</option>
								<option value="Mexico">Mexico</option>
								<option value="Midway Islands">Midway Islands</option>
								<option value="Moldova">Moldova</option>
								<option value="Monaco">Monaco</option>
								<option value="Mongolia">Mongolia</option>
								<option value="Montserrat">Montserrat</option>
								<option value="Morocco">Morocco</option>
								<option value="Mozambique">Mozambique</option>
								<option value="Myanmar">Myanmar</option>
								<option value="Nambia">Nambia</option>
								<option value="Nauru">Nauru</option>
								<option value="Nepal">Nepal</option>
								<option value="Netherland Antilles">Netherland Antilles</option>
								<option value="Netherlands">Netherlands (Holland, Europe)</option>
								<option value="Nevis">Nevis</option>
								<option value="New Caledonia">New Caledonia</option>
								<option value="New Zealand">New Zealand</option>
								<option value="Nicaragua">Nicaragua</option>
								<option value="Niger">Niger</option>
								<option value="Nigeria">Nigeria</option>
								<option value="Niue">Niue</option>
								<option value="Norfolk Island">Norfolk Island</option>
								<option value="Norway">Norway</option>
								<option value="Oman">Oman</option>
								<option value="Pakistan">Pakistan</option>
								<option value="Palau Island">Palau Island</option>
								<option value="Palestine">Palestine</option>
								<option value="Panama">Panama</option>
								<option value="Papua New Guinea">Papua New Guinea</option>
								<option value="Paraguay">Paraguay</option>
								<option value="Peru">Peru</option>
								<option value="Phillipines">Philippines</option>
								<option value="Pitcairn Island">Pitcairn Island</option>
								<option value="Poland">Poland</option>
								<option value="Portugal">Portugal</option>
								<option value="Puerto Rico">Puerto Rico</option>
								<option value="Qatar">Qatar</option>
								<option value="Republic of Montenegro">Republic of Montenegro</option>
								<option value="Republic of Serbia">Republic of Serbia</option>
								<option value="Reunion">Reunion</option>
								<option value="Romania">Romania</option>
								<option value="Russia">Russia</option>
								<option value="Rwanda">Rwanda</option>
								<option value="St Barthelemy">St Barthelemy</option>
								<option value="St Eustatius">St Eustatius</option>
								<option value="St Helena">St Helena</option>
								<option value="St Kitts-Nevis">St Kitts-Nevis</option>
								<option value="St Lucia">St Lucia</option>
								<option value="St Maarten">St Maarten</option>
								<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
								<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
								<option value="Saipan">Saipan</option>
								<option value="Samoa">Samoa</option>
								<option value="Samoa American">Samoa American</option>
								<option value="San Marino">San Marino</option>
								<option value="Sao Tome & Principe">Sao Tome &amp; Principe</option>
								<option value="Saudi Arabia">Saudi Arabia</option>
								<option value="Senegal">Senegal</option>
								<option value="Seychelles">Seychelles</option>
								<option value="Sierra Leone">Sierra Leone</option>
								<option value="Singapore">Singapore</option>
								<option value="Slovakia">Slovakia</option>
								<option value="Slovenia">Slovenia</option>
								<option value="Solomon Islands">Solomon Islands</option>
								<option value="Somalia">Somalia</option>
								<option value="South Africa">South Africa</option>
								<option value="Spain">Spain</option>
								<option value="Sri Lanka">Sri Lanka</option>
								<option value="Sudan">Sudan</option>
								<option value="Suriname">Suriname</option>
								<option value="Swaziland">Swaziland</option>
								<option value="Sweden">Sweden</option>
								<option value="Switzerland">Switzerland</option>
								<option value="Syria">Syria</option>
								<option value="Tahiti">Tahiti</option>
								<option value="Taiwan">Taiwan</option>
								<option value="Tajikistan">Tajikistan</option>
								<option value="Tanzania">Tanzania</option>
								<option value="Thailand">Thailand</option>
								<option value="Togo">Togo</option>
								<option value="Tokelau">Tokelau</option>
								<option value="Tonga">Tonga</option>
								<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
								<option value="Tunisia">Tunisia</option>
								<option value="Turkey">Turkey</option>
								<option value="Turkmenistan">Turkmenistan</option>
								<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
								<option value="Tuvalu">Tuvalu</option>
								<option value="Uganda">Uganda</option>
								<option value="Ukraine">Ukraine</option>
								<option value="United Arab Erimates">United Arab Emirates</option>
								<option value="United Kingdom">United Kingdom</option>
								<option value="United States of America">United States of America</option>
								<option value="Uraguay">Uruguay</option>
								<option value="Uzbekistan">Uzbekistan</option>
								<option value="Vanuatu">Vanuatu</option>
								<option value="Vatican City State">Vatican City State</option>
								<option value="Venezuela">Venezuela</option>
								<option value="Vietnam">Vietnam</option>
								<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
								<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
								<option value="Wake Island">Wake Island</option>
								<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
								<option value="Yemen">Yemen</option>
								<option value="Zaire">Zaire</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
			              	</select>
		                </div>
		              </div>
		
		              <div class="control-group">
		                <label class="control-label" for="txt_sshkey_MA">SSH Key:</label>
		                <div class="controls">
		                    <textarea id="txt_sshkey_MA" class="input-xlarge" rows="3" required="required"></textarea>
		                </div>
		              </div>
		              
		               <div class="control-group">
		                <label class="control-label" for="txt_motivation_MA">Motivation:</label>
		                <div class="controls">
		                    <textarea id="txt_motivation_MA" class="input-xlarge" rows="3" required="required"></textarea>
		                </div>
		              </div>
		              
                   	  <button id="btn_add_MA" class="btn btn-primary" type="submit">Add</button>
		            </form>
		            </div>
		            
		            
		        </div>
		        </div>              
            </div>
            
        </div>

        <div id="edit_modal" class="modal hide fade" style="margin-left:-300px;">
                <form class="well form-horizontal" id="form_modify_user">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">X</a>
              <h3>Edit user <span id="s_login_e"></span><span id="s_id_e" style="display:none"></span></h3>
              
            </div>
           <div class="modal-body" style="max-height:610px;">
               <div class="alert alert-error" id="div_error_edit" style="display:none"></div>
               

              <div class="control-group">
                <label class="control-label" for="txt_firstname_e">First Name:</label>
                <div class="controls">
                    <input id="txt_firstname_e" type="text" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_lastname_e">Last Name:</label>
                <div class="controls">
                    <input id="txt_lastname_e" type="text" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_login_e">Login:</label>
                <div class="controls">
                    <input id="txt_login_e" type="text" class="input-xlarge" required="required" disabled="disabled"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_email_e">Email:</label>
                <div class="controls">
                    <input id="txt_email_e" type="email" class="input-xlarge" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_structure_e">Structure:</label>
                <div class="controls">
                    <input id="txt_structure_e" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_city_e">City:</label>
                <div class="controls">
                    <input id="txt_city_e" type="text" class="input-xlarge" required="required" />
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_country_e">Country:</label>
                <div class="controls">
                    <input id="txt_country_e" type="text" class="input-xlarge" required="required" />
                </div>
              </div>
              
               <div class="control-group">
                <label class="control-label" for="txt_motivation_e">Motivation:</label>
                <div class="controls">
                    <textarea id="txt_motivation_e" class="input-xlarge" rows="3"></textarea>
                </div>
              </div>
              <div class="tabbable">
              	<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_SSH1_e" data-toggle="tab">SSH Key 1</a></li>
                	<li><a href="#tab_SSH2_e" data-toggle="tab">SSH Key 2</a></li>
					<li><a href="#tab_SSH3_e" data-toggle="tab">SSH Key 3</a></li>
					<li><a href="#tab_SSH4_e" data-toggle="tab">SSH Key 4</a></li>
					<li><a href="#tab_SSH5_e" data-toggle="tab">SSH Key 5</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="tab_SSH1_e">
						<div class="control-group">
							<textarea id="txt_sshkey_e" class="input-xlarge" rows="3" style="width:460px;"></textarea>
						</div>
					</div>
					<div class="tab-pane" id="tab_SSH2_e">
						<div class="control-group">
							<textarea id="txt_sshkey_e2" class="input-xlarge" rows="3" style="width:460px;"></textarea>
						</div>
					</div>
					<div class="tab-pane" id="tab_SSH3_e">
						<div class="control-group">
							<textarea id="txt_sshkey_e3" class="input-xlarge" rows="3" style="width:460px;"></textarea>
						</div>
					</div>
					<div class="tab-pane" id="tab_SSH4_e">
						<div class="control-group">
							<textarea id="txt_sshkey_e4" class="input-xlarge" rows="3" style="width:460px;"></textarea>
						</div>
					</div>
					<div class="tab-pane" id="tab_SSH5_e">
						<div class="control-group">
							<textarea id="txt_sshkey_e5" class="input-xlarge" rows="3" style="width:460px;"></textarea>
						</div>
					</div>

				</div>
			  </div>              
              
              
            </div>
            
            <div class="modal-footer">
                   <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
            </div>
                </form>
            
        </div>
        

<?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>
 
    <script type="text/javascript">

    var oTable;
    var users = {};
    var selectedUser = {};

    $(document).ready(function()
    {
        $('#add_modal').modal('hide');
        $('#edit_modal').modal('hide');

        /* Load data in the table */
        $.ajax({
            url: "/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
                users = data;
                var i = 0;
                $.each(data, function(key,val) {
                    var btnValidClass = "btn-primary";
                    var btnValidValue="Pending";
                    if(val.validate) {
                         btnValidClass="";
                         btnValidValue="Valid";
                    }

                    btnAdminClass = '';
                    btnAdminValue = "User";
                    if(val.admin) {
                        btnAdminClass = 'btn-warning';
                        btnAdminValue = "Admin";
                    }
 
                     //user row
                    $("#tbl_users tbody").append(
                    '<tr id=' + i + ' data=' + i + '>'+
                    '<td>' + val.login + '</td>'+
                    '<td class="firstName">' + val.firstName + '</td>'+
                    '<td class="lastName">'+ val.lastName +'</td>'+
                    '<td><a href="mailto:' + val.email + '" class="email">' + val.email + '</a></td>'+
                    '<td>'+ formatCreateTimeStamp(val.createTimeStamp) +'</td>'+
                    '<td><a href="#" class="btn btn-valid '+btnValidClass+'" data="'+i+'" data-state="'+val.validate+'" onClick="validateUser('+i+')">'+btnValidValue+'</a></td>' +
                    '<td><a href="#" class="btn btn-admin '+btnAdminClass+'" data="'+i+'" data-state="'+val.admin+'" onClick="setAdmin('+i+')">'+btnAdminValue+'</a></td>' +
                    '<td><a href="admin_exps.php?user='+val.login+'" class="btn btn-view" title="Experiments"><i class="icon icon-list"></i></a> ' +
                        '<a href="#" class="btn btn-edit" data-toggle="modal" data="'+i+'" title="Edit"><i class="icon icon-pencil"></i></a> ' +
                        '<a href="#" class="btn btn-del btn-danger" data="'+i+'" onClick="deleteUser('+i+')" title="Delete"><i class="icon-white icon-remove"></i></a></td>'
                    +'</tr>');
                    $("tr[data="+i+"] .btn-valid").width(50);
                    $("tr[data="+i+"] .btn-admin").width(50);
                    i++;
                });

                //action on edit button: load data on modal form
                $(".btn-edit").click(function(){
                    var userId = $(this).attr("data");
                    selectedUser = users[userId];
                    $('#s_login_e').html(selectedUser.login);
                    $('#s_id_e').html(userId);
                    $('#txt_sshkey_e').val(selectedUser.sshPublicKeys[0]);
                    $('#txt_sshkey_e2').val(selectedUser.sshPublicKeys[1]);
                    $('#txt_sshkey_e3').val(selectedUser.sshPublicKeys[2]);
                    $('#txt_sshkey_e4').val(selectedUser.sshPublicKeys[3]);
                    $('#txt_sshkey_e5').val(selectedUser.sshPublicKeys[4]);
                    $('#txt_firstname_e').val(selectedUser.firstName);
                    $('#txt_lastname_e').val(selectedUser.lastName);
                    $('#txt_login_e').val(selectedUser.login);
                    $('#txt_email_e').val(selectedUser.email);
                    $('#txt_structure_e').val(selectedUser.structure);
                    $('#txt_city_e').val(selectedUser.city);
                    $('#txt_country_e').val(selectedUser.country);
                    $('#txt_motivation_e').val(selectedUser.motivations);
                    $("#edit_modal").modal('show');
                });
                oTable = $('#tbl_users').dataTable({
                    "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
                        "bPaginate": true,
                        "sPaginationType": "bootstrap",
                        "bLengthChange": true,
                        "bFilter": true,
                        "bSort": true,
                        "bInfo": true,
                        "bAutoWidth": false
                } );
                $('#tbl_users').show();
                $('#tbl_exps_processing').hide();
                
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $('#tbl_exps_processing').hide();
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving user list");
            }
        });
    });
    
    /* Delete a user */
    function deleteUser(id) {
        if(confirm("Delete user?")) {
            var userdelete = users[id];
            $.ajax({
                url: "/rest/admin/users/"+userdelete.login,
                type: "DELETE",
                contentType: "application/json",
                dataType: "text",
            
                success:function(data){
                    //$("tr[data="+id+"]").remove()
                    oTable.fnDeleteRow(document.getElementById(id),true);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error: " + errorThrows);
                }
            });
        }
    };
    
    
    /* Toggle Valid state */
    function validateUser(id) {
        var state = $("tr[data="+id+"] .btn-valid").attr("data-state");
        var confirmText = "Validate user?";
        if(state=="true") confirmText="Unvalidate user?";

        if(confirm(confirmText)) {
            //toggle validate flag
            var user = users[id];
            user.validate = !user.validate;
            
            $.ajax({
                url: "/rest/admin/users/"+user.login,
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    if(state == "false") {
                        oTable.fnUpdate('<a href="#" class="btn btn-valid" data="'+id+'" data-state="true" onClick="validateUser('+id+')">Valid</a>',id,5,true);
                    } else {
                        oTable.fnUpdate('<a href="#" class="btn btn-valid btn-primary" data="'+id+'" data-state="false" onClick="validateUser('+id+')">Pending</a>',id,5,true);
                    }
                    $("tr[data="+id+"] .btn-valid").width(50);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows);
                }
            });
        };
    };
    
    
    /* Toggle Admin state */
    function setAdmin(id) {
        var state = $("tr[data="+id+"] .btn-admin").attr("data-state");
        var confirmText = "Set Admin state?";
        if(state=="true") confirmText="Unset Admin state?";


        if(confirm(confirmText)) {
            //toggle admin flag
            var user = users[id];
            user.admin = !user.admin;
            
            $.ajax({
                url: "/rest/admin/users/"+user.login,
                type: "PUT",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(user),
                success:function(data){
                    if(state == "false") {
                        oTable.fnUpdate('<a href="#" class="btn btn-admin btn-warning" data="'+id+'" data-state="true" onClick="setAdmin('+id+')">Admin</a>',id,6,true);
                    } else {
                        oTable.fnUpdate('<a href="#" class="btn btn-admin" data="'+id+'" data-state="false" onClick="setAdmin('+id+')">User</a>',id,6,true);
                    }
                    $("tr[data="+id+"] .btn-admin").width(50);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows);
                }
            });
        };
    };   
    
    
    /* Save values from edit modal */
    $('#form_modify_user').bind('submit', function(e)
    {
        e.preventDefault();
        
        //change all value
        id = $('#s_id_e').html();
        selectedUser.firstName = $("#txt_firstname_e").val();
        selectedUser.lastName = $("#txt_lastname_e").val();
        selectedUser.login = $("#txt_login_e").val();
        selectedUser.email = $("#txt_email_e").val();


        var sshKeys = [];
    
        sshKeys.push($("#txt_sshkey_e").val());
        sshKeys.push($("#txt_sshkey_e2").val());
        sshKeys.push($("#txt_sshkey_e3").val());
        sshKeys.push($("#txt_sshkey_e4").val());
        sshKeys.push($("#txt_sshkey_e5").val());
        
        selectedUser.sshPublicKeys = sshKeys;
        
        selectedUser.motivations = $("#txt_motivation_e").val();
        selectedUser.structure = $("#txt_structure_e").val();
        selectedUser.city = $("#txt_city_e").val();
        selectedUser.country = $("#txt_country_e").val();
        
        $.ajax({
            url: "/rest/admin/users/"+selectedUser.login,
            type: "PUT",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(selectedUser),
            success:function(data){
                $("#edit_modal").modal('hide');
                $("#div_error_edit").html("");
                oTable.fnUpdate (selectedUser.firstName, document.getElementById(id), 1, false);
                oTable.fnUpdate (selectedUser.lastName, document.getElementById(id), 2, false);
                oTable.fnUpdate ('<a href="mailto:' + selectedUser.email + '" class="email">' + selectedUser.email + '</a>', document.getElementById(id), 3, true);
                //oTable.fnUpdate (selectedUser.email, document.getElementById(id),3, true);
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_error_edit").html("An error occurred while saving user modifications");
                $("#div_error_edit").show();
            }
        });
        
    return false;
    
    });
    
    /* Add user(s) */
    $(".btn-add").click(function(){
        $("#add_modal").modal('show');
    });
    $('#form_add_SA').bind('submit', function(e){
    
        e.preventDefault();
        
        var userregister = {
        "firstName":$("#txt_firstname_SA").val(),
        "lastName":$("#txt_lastname_SA").val(),
        "email":$("#txt_email_SA").val(),
        "structure":$("#txt_structure_SA").val(),
        "city":$("#txt_city_SA").val(),
        "country":$("#txt_country_SA").val(),
        "sshPublicKey":$("#txt_sshkey_SA").val(),
        "motivations":$("#txt_motivation_SA").val(),
        "type":"SA"
        };
        
        
        $.ajax({
            url: "/rest/admin/users",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(userregister),
            success:function(data){
                 window.location.reload();
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                var errorMsg="Error";
                if(XMLHttpRequest.status == 409) errorMsg="This user is already registered";
                else if(XMLHttpRequest.status == 403) errorMsg="Error";
                else errorMsg=errorThrows; 
                $("#div_error_add").html(errorMsg); 
                $("#div_error_add").show();
            }
        });
        
        return false;
    
    });
    $('#form_add_MA').bind('submit', function(e){
    
        e.preventDefault();
        
        var userregister = {
        "login":$("#txt_login_MA").val(),
        "event":$("#txt_event_MA").val(),
        "structure":$("#txt_structure_MA").val(),
        "city":$("#txt_city_MA").val(),
        "country":$("#txt_country_MA").val(),
        "sshPublicKey":$("#txt_sshkey_MA").val(),
        "motivations":$("#txt_motivation_MA").val(),
        "nbUsersToAdd":$("#txt_howmany_MA").val(),
        "type":"MA"
        };
        
        
        $.ajax({
            url: "/rest/admin/users",
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(userregister),
            success:function(data){
                 window.location.reload();
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                var errorMsg="Error";
                if(XMLHttpRequest.status == 409) errorMsg="This user is already registered";
                else if(XMLHttpRequest.status == 403) errorMsg="Error";
                else errorMsg=errorThrows; 
                $("#div_error_add").html(errorMsg); 
                $("#div_error_add").show();
            }
        });
        
        return false;
    
    });

    function formatCreateTimeStamp(createTimeStamp) {
        /* "yyyy/mm/dd" */
        return createTimeStamp.substring(0,4)+"/"+createTimeStamp.substring(4,6)+"/"+createTimeStamp.substring(6,8);
    }
    
    
    </script>

  </body>
</html>
