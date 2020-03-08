import axios from "axios"

import "./validationRules"
import "./models"
import "loglevel"
import "./api"
import "./components/bootstrap"

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
