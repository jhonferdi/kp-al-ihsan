import Vue from 'vue'
import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
  faUser, faLock, faSignOutAlt, faCog, faSpinner,
  faEdit, faTimes, faCamera, faPlus,
} from '@fortawesome/free-solid-svg-icons'

import {
  faGithub
} from '@fortawesome/free-brands-svg-icons'

config.autoAddCss = false

library.add(
  faUser, faLock, faSignOutAlt, faCog, faSpinner,
  faEdit, faTimes, faCamera, faPlus,
  faGithub,
)

Vue.component('Fa', FontAwesomeIcon)
