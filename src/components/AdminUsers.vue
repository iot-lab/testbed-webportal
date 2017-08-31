<template>
<div class="container mt-3">
        
    <h2>Users</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="my-2">
                <span class="lead mr-3">Show</span>                
                <label class="custom-control custom-radio">
                  <input id="radioStacked1" name="radio-stacked" type="radio" class="custom-control-input" v-model="show" value="pending" @click="showPending">
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">Pending users</span>
                </label>
                <label class="custom-control custom-radio mr-0">
                  <input id="radioStacked2" name="radio-stacked" type="radio" class="custom-control-input" v-model="show" value="admin" @click="showAdmin">
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">Administrators</span>
                </label>
            </div>
            <form @submit.prevent="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for users" v-model="pattern">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-secondary" aria-label="Search"> <i class="fa fa-search"></i> </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 text-right align-self-end mb-4">
            <router-link to="/adduser" class="btn btn-success"><i class="fa fa-user-plus"></i> Add User·s</router-link>
        </div>
    </div>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="header headerSortDown" aria-sort="ascending">Login</th>
                <th class="header">FirstName</th>
                <th class="header">LastName</th>
                <th class="header">Email</th>
                <th class="header">Created</th>
                <th class="header" width="50px">State</th>
                <th class="header" width="50px">Role</th>
                <th class="header" width="50px">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user, i) in users">
                <td>{{user.login}}</td>
                <td>{{user.firstName}}</td>
                <td>{{user.lastName}}</td>
                <td><a :href="'mailto:' + user.email" class="email">{{user.email}}</a></td>
                <td :title="user.created | formatDateTime">{{user.created | formatDate}}</td>
                <td><a href="" class="btn btn-sm" :class="(user.status === 'pending') ? 'btn-primary' : 'btn-outline-primary'" style="width: 70px;">{{user.status}}</a></td>
                <td><a href="" class="btn btn-sm" :class="(user.isAdmin) ? 'btn-warning' : 'btn-outline-primary'" style="width: 70px;">missing</a></td>
                <td>
                    <div class="btn-group" role="group" aria-label="User actions">
                        <a href="" class="btn btn-sm border-0 btn-outline-secondary" title="Experiments"><i class="fa fa-fw fa-flask"></i></a>
                        <a href="" class="btn btn-sm border-0 btn-outline-secondary" title="Edit"><i class="fa fa-fw fa-pencil"></i></a>
                        <a href="" class="btn btn-sm border-0 btn-outline-secondary" title="Email"><i class="fa fa-fw fa-envelope"></i></a>
                        <a href="" class="btn btn-sm border-0 btn-outline-danger" title="Reset password"><i class="fa fa-fw fa-unlock-alt"></i></a>
                        <a href="" class="btn btn-sm border-0 btn-outline-danger" title="Delete"><i class="fa fa-fw fa-trash"></i></a>
                    </div>
                </td>
            </tr>                        
        </tbody>
    </table>

    <div class="text-muted">
        <p v-if="users && users.length == 1">{{users.length}} matching user</p>
        <p v-else-if="users && users.length > 1">{{users.length}} matching users</p>
        <p v-else>No matching user found</p>        
    </div>

    <!-- MODAL WINDOW FOR ADDING USER(S) -->
    <div id="add_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
    aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#xD7;</button>
                     <h3>Add user(s)</h3>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="div_error_add" style="display:none"></div>
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active nav-item"><a href="#tab_SA" data-toggle="tab" class="nav-link">Single Account Creation</a>
                            </li>
                            <li class="nav-item"><a href="#tab_MA" data-toggle="tab" class="nav-link">Multiple Accounts Creation</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- PANEL FOR SINGLE ACCOUNT CREATION FORM -->
                            <div class="tab-pane active" id="tab_SA">
                                <form class="card bg-light card-body mb-3 " id="form_add_SA">
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_firstname_SA">First Name:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_firstname_SA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_lastname_SA">Last Name:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_lastname_SA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_email_SA">Email:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_email_SA" type="email" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_organization_SA">Organization:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_organization_SA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_city_SA">City:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_city_SA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_country_SA">Country:</label>
                                        <div class="col-xl-8">
                                            <select id="txt_country_SA" class="form-control" required="required">
                                                <option value="Zimbabwe">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_sshkey_SA">SSH Key:</label>
                                        <div class="col-xl-8">
                                            <textarea id="txt_sshkey_SA" class="form-control" rows="3" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_motivation_SA">Motivation:</label>
                                        <div class="col-xl-8">
                                            <textarea id="txt_motivation_SA" class="form-control" rows="3" required="required"></textarea>
                                        </div>
                                    </div>
                                    <button id="btn_add_SA" class="btn btn-primary" type="submit">Add</button>
                                </form>
                            </div>
                            <!-- PANEL FOR MULTIPLE ACCOUNTS CREATION FORM -->
                            <div class="tab-pane" id="tab_MA">
                                <form class="card bg-light card-body mb-3 " id="form_add_MA">
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_howmany_MA">How many accounts:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_howmany_MA" type="text" class="form-control" required="required"
                                            value="1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_login_MA">Login:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_login_MA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_event_MA">Event:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_event_MA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_organization_MA">Organization:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_organization_MA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_city_MA">City:</label>
                                        <div class="col-xl-8">
                                            <input id="txt_city_MA" type="text" class="form-control" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_country_MA">Country:</label>
                                        <div class="col-xl-8">
                                            <select id="txt_country_MA" class="form-control" required="required">
                                                <option value="Zimbabwe">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_sshkey_MA">SSH Key:</label>
                                        <div class="col-xl-8">
                                            <textarea id="txt_sshkey_MA" class="form-control" rows="3" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xl-3 form-control-label" for="txt_motivation_MA">Motivation:</label>
                                        <div class="col-xl-8">
                                            <textarea id="txt_motivation_MA" class="form-control" rows="3" required="required"></textarea>
                                        </div>
                                    </div>
                                    <button id="btn_add_MA" class="btn btn-primary" type="submit">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- MODAL WINDOW FOR EDITING USER -->
    <div id="edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
    aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#xD7;</button>
                     <h3>Edit user <span id="s_login_e"></span><span id="s_id_e" style="display:none"></span></h3>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="div_error_edit" style="display:none"></div>
                    <form class="card bg-light card-body mb-3 " id="form_modify_user">
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_firstname_e">First Name:</label>
                            <div class="col-xl-8">
                                <input id="txt_firstname_e" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_lastname_e">Last Name:</label>
                            <div class="col-xl-8">
                                <input id="txt_lastname_e" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_login_e">Login:</label>
                            <div class="col-xl-8">
                                <input id="txt_login_e" type="text" class="form-control" required="required"
                                disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_email_e">Email:</label>
                            <div class="col-xl-8">
                                <input id="txt_email_e" type="email" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_organization_e">Organization:</label>
                            <div class="col-xl-8">
                                <input id="txt_organization_e" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_city_e">City:</label>
                            <div class="col-xl-8">
                                <input id="txt_city_e" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_country_e">Country:</label>
                            <div class="col-xl-8">
                                <input id="txt_country_e" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xl-3 form-control-label" for="txt_motivation_e">Motivation:</label>
                            <div class="col-xl-8">
                                <textarea id="txt_motivation_e" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="tabbable">
                            <ul class="nav nav-tabs">
                                <li class="active nav-item"><a href="#tab_SSH1_e" data-toggle="tab" class="nav-link">SSH Key 1</a>
                                </li>
                                <li class="nav-item"><a href="#tab_SSH2_e" data-toggle="tab" class="nav-link">SSH Key 2</a>
                                </li>
                                <li class="nav-item"><a href="#tab_SSH3_e" data-toggle="tab" class="nav-link">SSH Key 3</a>
                                </li>
                                <li class="nav-item"><a href="#tab_SSH4_e" data-toggle="tab" class="nav-link">SSH Key 4</a>
                                </li>
                                <li class="nav-item"><a href="#tab_SSH5_e" data-toggle="tab" class="nav-link">SSH Key 5</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_SSH1_e">
                                    <div class="form-group">
                                        <textarea id="txt_sshkey_e" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_SSH2_e">
                                    <div class="form-group">
                                        <textarea id="txt_sshkey_e2" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_SSH3_e">
                                    <div class="form-group">
                                        <textarea id="txt_sshkey_e3" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_SSH4_e">
                                    <div class="form-group">
                                        <textarea id="txt_sshkey_e4" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_SSH5_e">
                                    <div class="form-group">
                                        <textarea id="txt_sshkey_e5" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- MODAL WINDOW FOR EMAILING USER -->
    <div id="email_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
    aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#xD7;</button>
                     <h3>Send a mail to user <span id="s_login_email"></span></h3>
                </div>
                <div class="modal-body">
                    <form id="sendMail" class="card bg-light card-body mb-3">
                        <div class="form-group">
                            <input type="hidden" id="to" name="to" value="">
                            <label class="form-control-label">Subject:</label>
                            <input type="text" name="subject" class="form-control"
                            value="IoT-LAB registration rejection">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Message:</label>
                            <textarea rows="7" class="form-control" name="message">Dear user, Please consider re-filling the sign-up form, with an academic/professional
                                (non-/*gmail/hotmail/yahoo/personal*/ ) mail address. For your information,
                                I will reject this first subscription. Regards,</textarea>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Send Email">
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

</div> <!-- container -->

</template>

<script>
import vSelect from 'vue-select'
import countries from '@/assets/js/countries'
import categories from '@/assets/js/categories'
import {iotlab} from '@/rest'
import {auth} from '@/auth'

export default {
  name: 'AdminUsers',
  components: {vSelect},

  data () {
    return {
      users: [],
      countries: countries,
      categories: categories,
      auth: auth,
      pattern: '',
      show: 'pending',
    }
  },

  created () {
    iotlab.getUsers().then(data => { this.users = data })
  },

  methods: {
    async showPending () {
      this.pattern = ''
      this.users = await iotlab.getUsers({status: 'pending'})
    },
    async showAdmin () {
      this.pattern = ''
      this.users = await iotlab.getUsers({isAdmin: true})
    },
    async search () {
      if (this.pattern) {
        this.show = false
        this.users = await iotlab.getUsers({search: this.pattern})
      } else {
        this.show = 'pending'
        this.showPending()
      }
    },
  },
}
</script>

<style>

</style>
