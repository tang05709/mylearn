// 创建立方体
if (GUILayout.Button("创建立方体", GUILayout.Height(50)))
{
    GameObject obj = GameObject.CreatePrimitive(PrimitiveType.Cube);
    obj.AddComponent<Rigidbody>();
    obj.AddComponent<Renderer>().material.color = Color.red;
    obj.name = "Cube";
    obj.transform.position = new Vector3(0, 5f, 0);
}

if (GUILayout.Button("向左移动物体"))
{
    cube.transform.Translate(new Vector3(-5f, 0, 0));
}
if (GUILayout.Button("向右移动物体"))
{
    cube.transform.position = cube.transform.position + new Vector3(5f, 0, 0);
}
if (GUILayout.Button("放大物体"))
{
    cube.transform.localScale *= 2f;
}
if (GUILayout.Button("缩小物体"))
{
    cube.transform.localScale /= 2f;
}
if (GUILayout.Button("旋转物体"))
{
    cube.transform.Rotate(new Vector3(0, 10f, 0));
}
if (GUILayout.Button("围绕球体旋转物体"))
{
    cube.transform.RotateAround(cylinder.transform.position, Vector3.up, 10);
}
