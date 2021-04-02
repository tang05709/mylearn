import com.baomidou.mybatisplus.annotation.TableName;
import com.wuwenze.poi.annotation.Excel;
import lombok.Data;
import org.springframework.data.mongodb.core.mapping.Document;
import org.springframework.data.mongodb.core.mapping.Field;

@Data
@Document(collection = "collection_id")
@TableName("collection_id")
public class CollectionId {
    private String id;
    @Field("collectioin_name")
    private String collectionName;
    private Integer aid; // 自增id
}
