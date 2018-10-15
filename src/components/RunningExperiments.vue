<template>
<div class="mt-3 mb-4">
  <table class="table table-striped table-sm mt-3" v-if="expList.length">
    <thead>
      <tr>
        <th>Id</th>
        <th>User</th>
        <th>Elapsed</th>
        <th>State</th>
        <th>Ending</th>
        <th>Nodes</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="exp in expList">
        <td class="nowrap" v-if="isAdmin">
          <router-link :to="{name: 'experimentDetails', params: { id: exp.id }}">{{exp.id}}</router-link>
        </td>
        <td class="nowrap" v-else>{{exp.id}}</td>
        <td class="nowrap" v-if="isAdmin">
          <router-link :to="{name: 'users', query: { search: exp.user }}">{{exp.user}}</router-link>
        </td>
        <td class="nowrap" v-else>{{exp.user}}</td>
        <td class="durationProgress nowrap" :style="`text-align: center; --progress: ${experimentProgress(exp)}%`" :title="showRemaining(exp)">
          {{exp.effective_duration | humanizeDuration}}
          <small class="text-dark">({{experimentProgress(exp)}}%)</small>
        </td>
        <td class="nowrap"><span class="badge badge-state" :class="exp.state | stateBadgeClass">{{exp.state}}</span></td>
        <td class="nowrap" :title="showStarted(exp)">{{exp.start_date | formatDateTime(exp.submitted_duration)}}</td>
        <td style="width: 600px">
          <div class="ellipsis ellipsis-indicator cursor" @click="toggleEllipsis">
            <span class="text-muted nowrap mr-1" v-if="exp.nodes.length <= 1">({{exp.nodes.length}} node)</span>
            <span class="text-muted nowrap mr-1" v-else>({{exp.nodes.length}} nodes)</span>
            <span class="nodes mr-1" :class="{'comma': index + 1 < exp.nodes.length}"
              v-for="(node, index) in [...exp.nodes].sort((a, b) => nodeSortByHostname(a, b))">{{node | stripDomain}}</span>
            <!-- {{exp.nodes.join(', ')}} -->
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <p class="text-muted font-italic" v-else>
    No experiment currently running
  </p>

</div> <!-- container -->

</template>

<script>
import { auth } from '@/auth'

function getEventPath (event) {
  if (event.composedPath && event.composedPath()) return event.composedPath()
  if (event.path) return event.path

  let path = []
  let target = event.target

  while (target.parentNode) {
    path.push(target)
    target = target.parentNode
  }
  path.push(document, window)
  return path
}

export default {
  name: 'RunningExperiments',

  props: {
    expList: {
      type: Array,
      default: () => [],
    },
  },

  data () {
    return {
      isAdmin: auth.isAdmin,
    }
  },

  methods: {

    showStarted (exp) {
      return `started ${this.$options.filters.formatDateTime(exp.start_date)}`
    },

    showRemaining (exp) {
      return `remaining ${this.$options.filters.humanizeDuration(exp.submitted_duration - exp.effective_duration)}`
    },

    experimentProgress (exp) {
      return Math.min(100, Math.round(100 * exp.effective_duration / exp.submitted_duration))
    },

    nodeSortByHostname (a, b) {
      // Allow sorting of hostnames by domain then by servername
      // the trick is to append the servername after the domain (e.g 'a8-1.grenoble.iot-lab.info' -> 'grenoble.iot-lab.info.a8-1')
      // then it's possible to simply sort the strings
      let sortByHostname = function (node) {
        let chuncks = node.split('.')
        chuncks.push(chuncks.shift()) // or we could use chunks.reverse()
        return chuncks.join('.')
      }
      return sortByHostname(a).localeCompare(sortByHostname(b))
    },

    displayNodes (exp) {
      return exp.nodes.map(n => this.$options.filters.stripDomain(n)).sort().map(n => `<i>${n}</i>`).join(', ')
    },

    toggleEllipsis (event) {
      getEventPath(event).find(e => e.tagName === 'DIV').classList.toggle('ellipsis')
    },

  },

}
</script>

<style scoped>
.nowrap {
  white-space: nowrap;
}
td.nowrap {
  vertical-align: middle;
}
.ellipsis {
  max-width: calc(600px - 0.6rem);
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
}
.durationProgress::after,.durationProgress::before {
  top: calc(50% - 12px);
  height: 24px;
}
.nodes {
  white-space: nowrap;
  display: inline-block;
}
.nodes.comma::after {
  content: ", ";
}
.ellipsis-indicator {
  position: relative;
}
.ellipsis-indicator:hover::after {
  position: absolute;
  right: 0;
  top: calc(50% - 12px);
  /*top: 50%;*/
  top: 0;
  padding: 0 5px;
  font-family: fontawesome;
  content: "\f141";
  content: "\f0d7";
  content: "\f065";
  color: var(--dark);
  font-size: 1em;
  background: #ccc;
  border-radius: 5px;
}
</style>
