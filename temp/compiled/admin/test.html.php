<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>test</title>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/vue.js"></script>
</head>
<body>
<!--<div id="example">{{msg}}</div>-->
<script>
    var mixin = {
        methods: {
            foo: function () {
                console.log('foo')
            },
            conflicting: function () {
                console.log('from mixin')
            }
        }
    }
    var vm = new Vue({
        mixins: [mixin],
        methods: {
            bar: function () {
                console.log('bar')
            },
            conflicting: function () {
                console.log('from self')
            }
        }
    })
    vm.foo() // -> "foo"
    vm.bar() // -> "bar"
    vm.conflicting() // -> "from self"
</script>
</body>
</html>