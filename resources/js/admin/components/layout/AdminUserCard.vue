<template>
    <div class="user-card">
        <img class="img-thumbnail rounded-circle s120" :src="store.user.avatar" alt="">
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
