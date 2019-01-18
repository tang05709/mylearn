// 创建立方体
if (GUILayout.Button("创建立方体", GUILayout.Height(50)))
{
    GameObject obj = GameObject.CreatePrimitive(PrimitiveType.Cube);
    obj.AddComponent<Rigidbody>();
    obj.AddComponent<Renderer>().material.color = Color.red;
    obj.name = "Cube";
    obj.transform.position = new Vector3(0, 5f, 0);
}
