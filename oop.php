<?php // TASK: You have team with workers. Every worker can make Job and write title and count spend time. After time spending he get price for this time (1$/sec). At end of day he can get sum and clear all records from system
if ( ! isset($_SESSION)) session_start();
if ( ! isset($_SESSION['tasks'])) $_SESSION['tasks'] = Array();
if (isset($_POST['add_task'])) {
    $_POST['name'] = strip_tags($_POST['name']);
    $_POST['price'] = strip_tags($_POST['price']);
    $_SESSION['tasks'][] = Array( 'name' => $_POST['name'], 'price' => $_POST['price'] );
    echo json_encode($_SESSION['tasks'], JSON_PRETTY_PRINT);
} elseif (isset($_POST['day_end'])) {
    $sum = 0;
    foreach ($_SESSION['tasks'] as $task) $sum += $task['price'];
    echo $sum;
    $_SESSION['tasks'] = Array();
} else {
?><html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Tasker</title>
</head>
<body style="position:relative;">
    <div class="run_interface" style="position: fixed; bottom:0; width:100%; background: white; border-top: 1px solid silver; text-align: center;">
        <input class="task_name" type="text" style="font-size: 16px; line-height: 30px; height: 30px; width: 50%; margin: 10px;">
        <br>
        <input type="button" value="Go!" class="task_start" style="width: 20%; height: 100px; font-size: 18px; background: lightgreen;">
        <input type="button" value="Pause" class="task_pause" style="width: 20%; height: 100px; font-size: 18px; background: lightyellow;">
        <input type="button" value="Finish" class="task_finish" style="width: 20%; height: 100px; font-size: 18px; background: lightblue;">
        <div style="margin: 10px; font-size: 18px; ">
            <b>Task Time</b> <span class="task_time">0</span> sec
        </div>
    </div>
    <div class="works_list" style="border: 1px solid silver; width: 90%; margin: 10px; padding: 10px; font-size: 18px;">
        <?php foreach ($_SESSION['tasks'] as $task) { ?>
            <div class="work_done"><?=$task['name']?> - <span class="price_done"><?=$task['price']?></span></div>
        <?php } ?>
    </div>
    <br>
    <input type="button" value="End of Day" class="day_end" style="width: 40%; height: 100px; font-size: 18px;">
</body><script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script>

class Tasker {
    constructor() {
        this.time_counter = false
        this.taskTime = $('.task_time')
        this.taskName =  $('.task_name')
        this.#addEvents()
    }
     /***************
       Handlers
     ***************/
    #addEvents = () => {
        $('.task_start').on('click', this.taskStart)
        $('.task_pause').on('click', this.taskPause)
        $('.task_finish').on('click', this.taskFinish)
        $('.day_end').on('click', this.dayEnd)

    }
    /***************
        Methods
    ***************/
    taskStart = (name) => {

        if (!this.taskName.val() && typeof name !== 'string'){
            this.taskName.val('No name task')
        } else if(typeof name === 'string'){ this.taskName.val(name) }

        !this.time_counter ? this.time_counter = setInterval(() => this.taskTime.html( +this.taskTime.html() + 1 ) , 1000) : {}
    }
    taskPause = () => {
        clearInterval(this.time_counter)
        this.time_counter = false
    }
    #renderList = () => {
        $('.works_list').append(`<div class="work_done">${ this.taskName.val() } - <span class="price_done">${  +this.taskTime.html()  }</span></div>`)
    }
    #clearData = () => {
        this.taskTime.html('0')
        this.taskName.val('')
    }
   #addTask = () => {
        $.post('test.php', { add_task : 1, name :this.taskName.val(), price : +this.taskTime.html() }, data =>  console.log(data) )
    }
    taskFinish = () => {
        this.taskPause()
        this.#renderList()
        this.#addTask()
        this.#clearData()
    }
    dayEnd(){
        $.post('test.php', { day_end : 1 }, data => { alert(`TO PAY:${data} $`);  location.reload() })
    }

}
    /*** Init ****/
    const tasker = new Tasker()

    /******** Exemple controls *******/
    // tasker.taskStart( Taskname )
    // tasker.taskPause()
    // tasker.taskFinish()

</script>
</html>
<?php }