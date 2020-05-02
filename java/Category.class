package com.don.donaldblog.utils;

import com.don.donaldblog.model.Category;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

public class TreeView {
    private List<Category> data = new ArrayList<Category>(); // 传入的数据
    public TreeView(List<Category> data)
    {
        this.data = data;
    }

    /**
     * 以树形分级的形式获取列表
     * @return
     */
    public List<Category> makeTree()
    {
        List<Category> tree = new ArrayList<Category>();
        List<Category> root = getRootCategory();
        for(Category category:root) {
            category = getChildrenTree(category);
            tree.add(category);
        }
        return tree;
    }

    /**
     * 以列表的形式获取列表
     * @return
     */
    public List<Category> makeList()
    {
        List<Category> tree = new ArrayList<Category>();
        List<Category> root = getRootCategory();
        for(Category category:root) {
            tree.add(category);
            tree.addAll(getChildList(category));
        }
        return tree;
    }

    /**
     * 跟传入的分级获取当前级以及所有上级，并且获取当前级选中的值
     * @param category
     * @return
     */
    public Map<Integer, List<Category>> getCurrentAndParents(Category category)
    {
        // Integer 表示选中项， List<Category>表示父级列表，为了保证顺序，这里使用TreeMap
        Map<Integer, List<Category>> currentAndParent = new TreeMap<Integer, List<Category>>();
        // 如果已经是顶级分类, 直接获取顶级分类
        if (category.getParentId() == 0) {
            List<Category> cParent = new ArrayList<Category>();
            for(Category cate : data) {
                if (cate.getParentId() == 0) {
                    cParent.add(cate);
                }
            }
            Integer id = Integer.valueOf(category.getId());
            currentAndParent.put(id, cParent);
            return currentAndParent;
        }
        // 如果不是顶级分类，则先获取父级id
        // 第一个Integer 保存当前选中的值， 第二个Integer保存当前的父级id
        Map<Integer, Integer> ids = new TreeMap<Integer, Integer>();
        //  向上获取parent_id
        ids.put(category.getId(), category.getParentId());
        getParents(category, ids);

        for(Map.Entry<Integer, Integer> id : ids.entrySet()) {
            List<Category> parents = new ArrayList<Category>();
            for(Category cate : data) {
                if (cate.getParentId() == id.getValue()) {
                    parents.add(cate);
                }
            }
            currentAndParent.put(id.getKey(), parents);
        }
        if(currentAndParent.size() > 0) {
            return currentAndParent;
        }

        return null;
    }

    public List<Integer> getNextChildrenIds(Category category)
    {
        List<Integer> nextIds = new ArrayList<Integer>();
        for(Category cate : data) {
            if (cate.getParentId() == category.getId()) {
               nextIds.add(cate.getId());
            }
        }
        return nextIds;
    }

    public List<Integer> getAllChildrenIds(Category category)
    {
        List<Integer> nextAllIds = new ArrayList<Integer>();
        List<Category> childrenList = getChildList(category);
        for(Category cate : childrenList) {
            nextAllIds.add(cate.getId());
        }
        return nextAllIds;
    }


    /**
     * 以树形的形式获取子分类
     * @param parent
     * @return
     */
    private Category getChildrenTree(Category parent)
    {
        List<Category> children = new ArrayList<Category>();
        for(Category category:data) {
            if (category.getParentId() == parent.getId()) {
                children.add(getChildrenTree(category));
            }
        }
        parent.setChildren(children);
        return parent;
    }

    /**
     * 以列表的形式获取子分类
     * @param parent
     * @return
     */
    private List<Category> getChildList(Category parent)
    {
        List<Category> children = new ArrayList<Category>();
        for(Category category:data) {
            if (category.getParentId() == parent.getId()) {
                children.add(category);
                children.addAll(getChildList(category));
            }
        }
        return children;
    }

    /**
     * 获取顶级分类
     * @return
     */
    private List<Category> getRootCategory()
    {
        List<Category> root = new ArrayList<Category>();
        for(Category category:data) {
            if (category.getParentId() == 0) {
                root.add(category);
            }
        }
        return root;
    }

    /**
     * 根据当前级获取上级的id以及当前级选中的id
     * @param category
     * @param ids
     * @return
     */
    private Map<Integer, Integer> getParents(Category category, Map<Integer, Integer> ids)
    {
        for(Category cate:data) {
            if (category.getParentId() == cate.getId()) {
                Integer id = Integer.valueOf(cate.getId());
                Integer parentId = Integer.valueOf(cate.getParentId());

                ids.put(id, parentId);
                if (!parentId.equals(Integer.valueOf(0))) {
                    getParents(cate, ids);
                }
            }
        }
        return ids;
    }
}
