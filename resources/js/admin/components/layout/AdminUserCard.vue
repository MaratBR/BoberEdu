<template>
    <div class="user-card">
        <div class="avatar s90">
            <img :src="'/storage/' + store.user.avatar" alt="">
        </div>
        <span>{{ store.user.name }}</span>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "AdminUserCard"
    })
    export default class AdminUserCard extends AdminStoreComponent {

    }
</script>

<style scoped lang="scss">
    $bg: rgba(white, 0.08);

    .user-card {
        display: flex;
        background-image: linear-gradient($bg, transparent);
        align-items: center;
        flex-direction: column;
        padding-bottom: 30px;
        padding-top: 10px;
    }
</style>
