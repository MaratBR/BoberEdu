import {extend} from "vee-validate";
import * as rules from 'vee-validate/dist/rules';
// @ts-ignore webpack don't complain but PhpStorm does for some reason
import { messages } from 'vee-validate/dist/locale/en.json';

const overrides = {
    required: {
        message: 'This field is required'
    }
};

for (let [rule, validation] of Object.entries(rules)) {
    extend(rule, {
        ...validation,
        ...{message: messages[rule]},
        ...(overrides[rule] || {})
    });
}
