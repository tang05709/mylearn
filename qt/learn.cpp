/**
 * @brief 获取树形菜单顶层
 * @param item
 * @return
 */
QStandardItem* Widget::getTopParent(QStandardItem *item)
{
    QStandardItem *secondItem = item;
    while (item->parent() != 0) {
        secondItem = item->parent();
        item = secondItem;
    }
    if (secondItem->index().column() == 0) {
        QStandardItemModel *model = static_cast<QStandardItemModel*>(ui->treeView->model());
        secondItem = model->itemFromIndex(secondItem->index().sibling(secondItem->index().row(), 0));
    }
    return secondItem;
}

/**
 * @brief 获取树形菜单顶层
 * @param itemIndex
 * @return
 */
QModelIndex Widget::getTopParent(QModelIndex itemIndex)
{
    QModelIndex secondItem = itemIndex;
    while (itemIndex.parent().isValid()) {
        secondItem = itemIndex.parent();
        itemIndex = secondItem;
    }
    if (secondItem.column() != 0) {
        secondItem = secondItem.sibling(secondItem.row(), 0);
    }
    return secondItem;
}
