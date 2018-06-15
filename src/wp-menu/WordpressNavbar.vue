<template>
  <header class="navbar navbar-light bg-white navbar-onelab navbar-expand-md" role="banner">
    <div class="container">
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span> &#x2630;
      </button> 

      <a href="/"> 
        <img src="../assets/fit-iotlab3.png">
      </a>

      <nav class="navbar-collapse collapse navbar-ex1-collapse" role="navigation">
        <div v-html="topmenu"></div>
        <ul class="nav navbar-nav ml-auto">
          <li class="nav-item" :class="{'active': $route.name === 'drawgantt'}">
            <router-link :to="{name: 'drawgantt'}" title="Testbed Activity" class="nav-link"><i class="fa fa-fw fa-calendar"></i> Activity</router-link>
          </li>
          <li class="nav-item" :class="{'active': $route.name !== 'drawgantt'}">
            <router-link :to="{name: 'dashboard'}" title="Testbed" class="nav-link"><i class="fa fa-wrench"></i> Testbed</router-link>
          </li>
        </ul>
      </nav>
      <!--/.nav-collapse -->
    </div>
    <!--/.container -->
  </header>
</template>

<script>
// import iotlab wordpress top menu
import template from '@/wp-menu/wp-menu.html'
import { replaceAll } from '@/utils'
import $ from 'jquery'

function convertToBootstrap4 (menu) {
  /* Convert top menu from bootstrap 3 to bootstrap 4 */

  // remove domain from links url
  menu = replaceAll(menu, 'href="https://www.iot-lab.info', 'href="')
  menu = replaceAll(menu, 'href="https://devwww.iot-lab.info', 'href="')

  var $html = $('<div>', {html: menu})

  // add nav-link class
  $html.find('ul.nav > li > a').addClass('nav-link')

  // add class for carets
  $html.find('a[data-toggle="dropdown"]').addClass('dropdown-toggle')

  // remove carets span
  $html.find('span.caret').remove()

  return $html.html()
}

export default {
  name: 'WordpressNavbar',
  data () {
    return {
      topmenu: convertToBootstrap4(template),
    }
  },

  mounted () {
    // Show dropdown menu on hover
    $('body').on('mouseenter mouseleave', '.navbar-onelab .dropdown', function (e) {
      $('.navbar-onelab .dropdown').removeClass('show')
      $('.navbar-onelab .dropdown-menu').removeClass('show')
      var dropdown = $(e.target).closest('.dropdown')
      var menu = $('.dropdown-menu', dropdown)
      dropdown.addClass('show')
      menu.addClass('show')
      setTimeout(function () {
        dropdown[dropdown.is(':hover') ? 'addClass' : 'removeClass']('show')
        menu[dropdown.is(':hover') ? 'addClass' : 'removeClass']('show')
      }, 200)
    })
  },
}
</script>

<style>
.navbar-onelab img {
  /*margin: 7px;*/
  margin-left: -4px;
  padding: 2px;
  height: 47px;
}
.navbar-onelab.navbar-light .navbar-nav .nav-link {
  text-transform: uppercase;
  color: #4480ca;
  font-family: open_sansbold;
  font-size: 10pt;
  font-size: 13.3333px;
  /*font-weight: normal;*/
  /*line-height: 0.8em;*/
  letter-spacing: 0.4pt;
  padding-left: 15px;
  padding-right: 15px;
}
.navbar-onelab .navbar-nav > li.active > a {
  position: relative;
  color: rgb(32, 83, 157) !important;
}
.navbar-onelab .navbar-nav > li:hover > a {
  position: relative;
  color: rgb(32, 83, 157) !important;
}
.navbar-onelab .navbar-nav > li.active > a:before {
  content: '';
  position: absolute;
  bottom: -14px;
  left: 0;
  right: -10px;
  height: 1px;
  border-bottom: solid 3px rgb(139, 176, 222);
}
.navbar-onelab .navbar-nav > li.show > a:before {
  content: '';
  position: absolute;
  bottom: -14px;
  left: 0;
  right: -10px;
  height: 1px;
  border-bottom: solid 3px rgb(32, 83, 157);
}
.navbar-onelab .dropdown-menu > li > a {
  display: block;
  padding: 8px 35px;
  clear: both;
  font-weight: bold;
  line-height: 1.42857143;
  color: #4480ca;
  white-space: nowrap;
  text-transform: uppercase;
  font-size: 14px;
  text-decoration: none;
}
.navbar-onelab .dropdown-menu > li > a:hover {
  color: rgb(32, 83, 157) !important;
}
.navbar-onelab .dropdown-menu {
  transform: translateY(11px);
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

</style>
