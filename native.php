<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Tasker</title>
</head>
<body style="position:relative;">
	<div id="tasker">
		<div class="works_list" id="works_list" style="border: 1px solid silver; width: 50%; margin: 10px; padding: 10px; font-size: 18px; margin:0 auto;">
			<?php foreach ($_SESSION['tasks'] as $task) { ?>
			<div class="work_done"><?=$task['name']?> - <span class="price_done"><?=$task['price']?></span></div>
			<?php } ?>
		</div>
		<br>
		<input type="button" value="End of Day" class="day_end" id="day_end" style="width: 40%; height: 100px; font-size: 18px; display: block; margin:0 auto;">
		<div class="run_interface" id="run_interface" style="position: fixed; bottom:0; width:100%; background: white; border-top: 1px solid silver; text-align: center;">
			<input class="task_name" id="task_name" type="text" style="font-size: 16px; line-height: 30px; height: 30px; width: 50%; margin: 10px;">
			<br>
			<input type="button" value="Go!" id="task_start" class="task_start" style="width: 20%; height: 100px; font-size: 18px; background: lightgreen;">
			<input type="button" value="Pause" id="task_pause" class="task_pause" style="width: 20%; height: 100px; font-size: 18px; background: lightyellow;">
			<input type="button" value="Finish" id="task_finish" class="task_finish" style="width: 20%; height: 100px; font-size: 18px; background: lightblue;">
			<div style="margin: 10px; font-size: 18px; ">
				<b>Task Time</b> <span id="task_time" class="task_time">0</span> sec
			</div>
			<br>
		</div>
	</div>

</body>
<script>

    let time_counter = false
    /***************
        Nodes
     ***************/
    const timeTracker = document.getElementById('tasker')
    const taskTime = timeTracker.querySelector('#task_time')
    const taskName = timeTracker.querySelector('#task_name')
    const tasklist = timeTracker.querySelector('#works_list')
    const pauseBtn = timeTracker.querySelector('#task_pause')
    const finishBtn = timeTracker.querySelector('#task_finish')
    const startBtn = timeTracker.querySelector('#task_start')
    const endBtn = timeTracker.querySelector('#day_end')

    /***************
         Methods
     ***************/
    const taskStart = () => !time_counter ? time_counter = setInterval(() => taskTime.innerHTML = +taskTime.innerHTML + 1  , 1000) : {}
    const taskPause = () =>  {clearInterval(time_counter); time_counter = false}
    const renderList = () => tasklist.insertAdjacentHTML('afterbegin', `<div class="work_done">${ taskName.value } - <span class="price_done">${  +taskTime.innerHTML  }</span></div>`)

    const clearData = () => { taskTime.innerHTML = '0'; taskName.value  = '' }

    const request = (taskData, type) => {
        const formData = new FormData()
        for(key in taskData){ formData.append(key, taskData[key]) }
        const xhr = new XMLHttpRequest()
        xhr.open('POST', '/ajax_api/tasker')
        xhr.send(formData)
        xhr.onload = () => {
            if(type === 'dayEnd'){ alert(`TO PAY: ${xhr.response} $ `);  location.reload() }
            else console.log(xhr.response)
        }
    }

    const taskFinish = () => {
        taskPause()
        renderList()
        request({ add_task : 1, name :taskName.value, price : +taskTime.innerHTML }, 'finish')
        clearData()
    }
    const dayEnd = () => request({ day_end : 1 }, 'dayEnd')

    /***************
        Handlers
     ***************/
    startBtn.addEventListener('click', taskStart)
    pauseBtn.addEventListener('click', taskPause)
    finishBtn.addEventListener('click', taskFinish)
    endBtn.addEventListener('click', dayEnd)

</script>
</html>