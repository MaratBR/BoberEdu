import {Component} from "vue";

type AdminPanelItem = {
    name: string
    display: string
    requiresRoles?: string[]
    children?: AdminPanelItem[],
    component: any
};

let i = (name: string, display: string, component: any, children?: AdminPanelItem[], roles?: string[]) => {
    return {name, component, display, children, requiresRoles: roles} as AdminPanelItem
}

type AdminPanelMap = AdminPanelItem[];

let map: AdminPanelMap = [
    i('c', 'Courses', import('./AdminPanel.vue'), [
        i('new', 'New', import('./CourseForm.vue'))
    ])
];

export default map;
