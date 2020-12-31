<template>
    <div>
        <div>
            <list-asset-history></list-asset-history>
        </div>
    </div>
</template>

<script>
import ListAssetHistory from "../../components/Asset/AssetHistory";
import axios from "axios";

export default {
    name: "ListRequest",
    components: {
        ListAssetHistory,
    }
}
</script>

<style scoped>

</style>
