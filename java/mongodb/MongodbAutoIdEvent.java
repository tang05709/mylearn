import cc.gos.bms.common.annotation.AutoId;
import cc.gos.bms.system.domain.CollectionId;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.mongodb.core.FindAndModifyOptions;
import org.springframework.data.mongodb.core.MongoTemplate;
import org.springframework.data.mongodb.core.mapping.event.AbstractMongoEventListener;
import org.springframework.data.mongodb.core.mapping.event.BeforeConvertEvent;
import org.springframework.data.mongodb.core.query.Criteria;
import org.springframework.data.mongodb.core.query.Query;
import org.springframework.data.mongodb.core.query.Update;
import org.springframework.stereotype.Component;
import org.springframework.util.ReflectionUtils;

import java.lang.reflect.Field;

@Component
public class MongodbAutoIdEvent extends AbstractMongoEventListener<Object> {
    @Autowired
    MongoTemplate mongoTemplate;

    @Override
    public void onBeforeConvert(BeforeConvertEvent<Object> event) {
        Object source = event.getSource();
        if (null != source) {
            ReflectionUtils.doWithFields(source.getClass(), new ReflectionUtils.FieldCallback() {
                @Override
                public void doWith(Field field) throws IllegalArgumentException, IllegalAccessException {
                    ReflectionUtils.makeAccessible(field);
                    if (field.isAnnotationPresent(AutoId.class)) {
                        field.set(source, getNextId(source.getClass().getSimpleName()));
                    }
                }
            });
        }
        super.onBeforeConvert(event);
    }

    private Integer getNextId(String collectionName)
    {
        Query query = new Query(Criteria.where("collectioin_name").is(collectionName));
        Update update = new Update();
        update.inc("aid", 1);
        FindAndModifyOptions options = new FindAndModifyOptions();
        options.upsert(true);
        options.returnNew(true);
        CollectionId collectionId = mongoTemplate.findAndModify(query, update, options, CollectionId.class);
        return collectionId.getAid();
    }
}
