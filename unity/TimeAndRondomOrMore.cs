GUILayout.Label("当前游戏时间： " + Time.time); // 从游戏开始时记时，截至到目前共运行的时间， 受Time.Scale影响，游戏暂停时时间不增加
GUILayout.Label("游戏时间的缩放： " + Time.timeScale); // 时间流逝的速度
GUILayout.Label("上一帧所消耗的时间： " + Time.deltaTime); // 上一帧所消耗的时间
GUILayout.Label("固定增量时间： " + Time.fixedTime); // 每一次执行FixedUpdate()函数的时间间隔
GUILayout.Label("上一帧所消耗的固定时间： " + Time.fixedDeltaTime); // 固定更新上一帧所消耗的时间
GUILayout.Label("真实逝去的时间： " + Time.realtimeSinceStartup); // 从游戏开始时记时，截至到目前共运行的真实时间， 不受Time.Scale影响，
                                                               //游戏暂停时该时间仍然增加

int i = Random.Range(0, 10);
float f = Random.Range(0f, 10f);
